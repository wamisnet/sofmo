#include <sofmo.h>
sofmo modem;
#define ans 2
#define redLED 4
#define greenLED 5
int state = 0, val, count;
//state:QorAか区別val:入力を判断count:ループ用
char statevoice;//音で送るデータ
void setup() {
  modem.begin();
  Serial.begin(9600);
  pinMode(ans, OUTPUT);//クイズstartLED
  pinMode(redLED, OUTPUT);
  //押した人を判断（点灯LED）＋正解表示（点滅LED）
  pinMode(greenLED, OUTPUT);//不正解の時に点滅するLED
  for (count = 14; count <= 17; count++) {
    pinMode(count, OUTPUT);
  }
}

void loop() {
  while (modem.available()) {
    char c = modem.read();
    if (c == '0' && state == 0) {
      statevoice = '0';
      digitalWrite(ans, HIGH);//クイズstartLED
      digitalWrite(greenLED, LOW);//押した人を判別LED
      digitalWrite(redLED, HIGH);//赤色LED消すため
      do {
        val = PINB & _BV(0);//8pinの入力を読み込む。digitalwriteの代わり
        if (val == 1 && statevoice == '0') {
          statevoice = '1';
          sendans(statevoice);
        }
        val = PINB & _BV(1);//9pinの入力を読み込む。digitalwriteの代わり
        if (val == 2 && statevoice == '0') {
          statevoice = '2';
          sendans(statevoice);
        }
        val = PINB & _BV(2);//10pinの入力を読み込む。digitalwriteの代わり
        if (val == 4 && statevoice == '0') {
          statevoice = '3';
          sendans(statevoice);
        }
        val = PINB & _BV(3);//11pinの入力を読み込む。digitalwriteの代わり
        if (val == 8 && statevoice == '0') {
          statevoice = '4';
          sendans(statevoice);
        }

      } while (statevoice == '0');
      digitalWrite(ans, LOW);
      state = 1;

    } else if (c == '6' && state == 1) {
      ansLED(0);
      state = 0;
    } else if (c == '5' && state == 1) {
      ansLED(1);
      state = 0;
    }
  }
}
void ansLED(int ansa) {
  int count;
  if (ansa == 1) {
    for (count = 0; count <= 5; count++) {
      digitalWrite(greenLED, HIGH);
      delay(100);
      digitalWrite(greenLED, LOW);
      delay(100);
    }
  } else {
    digitalWrite(greenLED, HIGH);
    for (count = 0; count <= 5; count++) {
      digitalWrite(redLED, HIGH);
      delay(100);
      digitalWrite(redLED, LOW);
      delay(100);
    }
  }
  for (count = 14; count <= 17; count++) {
    digitalWrite(count, LOW);
  }
}
void sendans(char pushnum) {
  int i = (int)pushnum - (int)'0';
  modem.write(pushnum);
  digitalWrite(i + 13, HIGH);
}
