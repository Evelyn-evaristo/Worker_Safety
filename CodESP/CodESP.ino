#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"

// Wi-Fi
const char* ssid = "Mg";
const char* password = "naotemsenha";

// ALTERAR
const char* API_SALVAR = "https://worker-safety-fkg4.onrender.com/salvar_dados.php";

unsigned long ultimaTentativaWiFi = 0;
const unsigned long intervaloReconexaoWiFi = 5000;

// Setores
const int SETOR_ID_SERVIDORES = 1;
const int SETOR_ID_DOCUMENTOS = 2;

const char* NOME_SERVIDORES = "Servidores";
const char* NOME_DOCUMENTOS = "Documentos";


// Limites
const float LIMITE_TEMPERATURA = 30.0;
const float LIMITE_UMIDADE = 85.0;

// Sensores e Buzzers (localização)
#define DHTTYPE DHT11

#define DHTPIN_SERVIDORES 4
#define DHTPIN_DOCUMENTOS 15

#define BUZZER_SERVIDORES 18
#define BUZZER_DOCUMENTOS 5

DHT dhtServidores(DHTPIN_SERVIDORES, DHTTYPE);
DHT dhtDocumentos(DHTPIN_DOCUMENTOS, DHTTYPE);

bool buzzerAtivoServidores = true;
bool buzzerAtivoDocumentos = true;

// Tempo
unsigned long ultimaLeitura = 0;
const unsigned long intervaloLeitura = 5000;

unsigned long ultimoEnvio = 0;
const unsigned long intervaloEnvio = 5000;

// Buzzer
struct Sirene {
  uint8_t pin;
  bool ativo;
  uint8_t fase;
  unsigned long ultimaTroca;
};

Sirene sireneServidores = {BUZZER_SERVIDORES, false, 0, 0};
Sirene sireneDocumentos = {BUZZER_DOCUMENTOS, false, 0, 0};

const unsigned long fasesMs[6] = {180, 120, 180, 120, 220, 700};

void atualizarSirene(Sirene &s) {
  if (!s.ativo) {
    digitalWrite(s.pin, LOW);
    s.fase = 0;
    return;
  }

  unsigned long agora = millis();
  bool estadoOn = (s.fase == 0 || s.fase == 2 || s.fase == 4);
  digitalWrite(s.pin, estadoOn ? HIGH : LOW);

  if (agora - s.ultimaTroca >= fasesMs[s.fase]) {
    s.ultimaTroca = agora;
    s.fase = (s.fase + 1) % 6;
  }
}

// Wi-Fi (mensagem)
void conectarWiFi() {
  Serial.println("Conectando ao Wi-Fi...");
  WiFi.begin(ssid, password);

  unsigned long inicio = millis();
  const unsigned long tempoLimite = 20000;

  while (WiFi.status() != WL_CONNECTED && millis() - inicio < tempoLimite) {
    delay(500);
    Serial.print(".");
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConectado!");
    Serial.print("IP ESP: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\nFalha ao conectar Wi-Fi.");
  }
}

// Enviar leitura
bool enviarLeitura(int setorId, float temp, float umid, bool alerta, const String &motivo) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Wi-Fi indisponível, envio cancelado.");
    return false;
  }

  HTTPClient http;
  http.begin(API_SALVAR);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String dados =
    "setor_id=" + String(setorId) +
    "&temperatura=" + String(temp, 2) +
    "&umidade=" + String(umid, 2);

  int code = http.POST(dados);

  if (code <= 0) {
    Serial.printf("Envio setor %d falhou. HTTP=%d\n", setorId, code);
    http.end();
    return false;
  }

  String resposta = http.getString();
  http.end();

  Serial.printf("Envio setor %d -> HTTP %d | %s\n", setorId, code, resposta.c_str());
  return (code >= 200 && code < 300);
}

// Monitor Serial (visualização)
void printBlocoSerial(
  float tempServidores,
  float umidServidores,
  float tempDocumentos,
  float umidDocumentos,
  bool alertaServidores,
  bool alertaDocumentos
) {
  Serial.println();
  Serial.println("================ MONITORAMENTO ================");

  Serial.printf("%s | Temp: %.1f C | Umidade: %.1f %% | %s\n",
                NOME_SERVIDORES,
                tempServidores,
                umidServidores,
                alertaServidores ? "ALERTA" : "OK");

  Serial.printf("%s | Temp: %.1f C | Umidade: %.1f %% | %s\n",
                NOME_DOCUMENTOS,
                tempDocumentos,
                umidDocumentos,
                alertaDocumentos ? "ALERTA" : "OK");

  Serial.println("===============================================");
}

// Setup
void setup() {
  Serial.begin(115200);
  Serial.println("Iniciando Worker Safety...");

  WiFi.mode(WIFI_STA);
  WiFi.setSleep(false);
  conectarWiFi();

  dhtServidores.begin();
  dhtDocumentos.begin();

  pinMode(BUZZER_SERVIDORES, OUTPUT);
  pinMode(BUZZER_DOCUMENTOS, OUTPUT);

  digitalWrite(BUZZER_SERVIDORES, LOW);
  digitalWrite(BUZZER_DOCUMENTOS, LOW);

  ultimaLeitura = millis() - intervaloLeitura;
  ultimoEnvio = millis() - intervaloEnvio;
}

// Loop
void loop() {
  unsigned long agora = millis();

  if (WiFi.status() != WL_CONNECTED) {
    if (agora - ultimaTentativaWiFi >= intervaloReconexaoWiFi) {
      ultimaTentativaWiFi = agora;
      Serial.println("Wi-Fi caiu, reconectando...");
      WiFi.disconnect();
      conectarWiFi();
    }
  }

  static float temperaturaServidores = NAN;
  static float umidadeServidores = NAN;
  static float temperaturaDocumentos = NAN;
  static float umidadeDocumentos = NAN;

  static bool alertaServidores = false;
  static bool alertaDocumentos = false;

  static String motivoServidores = "";
  static String motivoDocumentos = "";

  if (agora - ultimaLeitura >= intervaloLeitura) {
    ultimaLeitura = agora;

    temperaturaServidores = dhtServidores.readTemperature();
    umidadeServidores = dhtServidores.readHumidity();

    temperaturaDocumentos = dhtDocumentos.readTemperature();
    umidadeDocumentos = dhtDocumentos.readHumidity();

    if (isnan(temperaturaServidores) || isnan(umidadeServidores)) {
      alertaServidores = true;
      motivoServidores = "ERRO_SENSOR_SERVIDORES";
      temperaturaServidores = 0.0;
      umidadeServidores = 0.0;
    } else {
      alertaServidores =
        temperaturaServidores > LIMITE_TEMPERATURA ||
        umidadeServidores > LIMITE_UMIDADE;

      motivoServidores = alertaServidores ? "ALERTA_SERVIDORES" : "";
    }

    if (isnan(temperaturaDocumentos) || isnan(umidadeDocumentos)) {
      alertaDocumentos = true;
      motivoDocumentos = "ERRO_SENSOR_DOCUMENTOS";
      temperaturaDocumentos = 0.0;
      umidadeDocumentos = 0.0;
    } else {
      alertaDocumentos =
        temperaturaDocumentos > LIMITE_TEMPERATURA ||
        umidadeDocumentos > LIMITE_UMIDADE;

      motivoDocumentos = alertaDocumentos ? "ALERTA_DOCUMENTOS" : "";
    }

    sireneServidores.ativo = alertaServidores && buzzerAtivoServidores;
    sireneDocumentos.ativo = alertaDocumentos && buzzerAtivoDocumentos;

    printBlocoSerial(
      temperaturaServidores,
      umidadeServidores,
      temperaturaDocumentos,
      umidadeDocumentos,
      alertaServidores,
      alertaDocumentos
    );
  }

  if (agora - ultimoEnvio >= intervaloEnvio) {
    ultimoEnvio = agora;

    enviarLeitura(
      SETOR_ID_SERVIDORES,
      temperaturaServidores,
      umidadeServidores,
      alertaServidores,
      motivoServidores
    );

    enviarLeitura(
      SETOR_ID_DOCUMENTOS,
      temperaturaDocumentos,
      umidadeDocumentos,
      alertaDocumentos,
      motivoDocumentos
    );
  }

  atualizarSirene(sireneServidores);
  atualizarSirene(sireneDocumentos);
}