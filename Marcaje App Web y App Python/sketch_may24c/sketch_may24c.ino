#include <SPI.h>
#include <MFRC522.h>

#define RST_PIN  9
#define SS_PIN   10

MFRC522 mfrc522(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(9600);      // Comunicación con la PC / Python
  SPI.begin();             // Inicializar bus SPI
  mfrc522.PCD_Init();      // Inicializar lector RFID
}

void loop() {
  if (!mfrc522.PICC_IsNewCardPresent()) return;
  if (!mfrc522.PICC_ReadCardSerial()) return;

  // Imprimir el UID en hexadecimal sin espacios ni texto adicional
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10)
      Serial.print("0"); // Asegura que cada byte tenga 2 caracteres
    Serial.print(mfrc522.uid.uidByte[i], HEX);
  }
  Serial.println(); // Termina con salto de línea para facilitar lectura en Python

  mfrc522.PICC_HaltA(); // Detiene la tarjeta
  delay(1000);          // Pequeño retardo para evitar lecturas duplicadas
}
