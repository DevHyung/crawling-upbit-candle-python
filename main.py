import json
import requests
import time
import pymysql
DB_HOST = 'localhost'
DB_ID = 'root'
DB_PW = 'autoset'
DB_NAME = 'upbit'
conn = pymysql.connect(host=DB_HOST, user=DB_ID,  passwd=DB_PW,db= DB_NAME, charset="utf8")
curs = conn.cursor()
sql_del = """delete from coin"""
sql_log = """INSERT INTO log(name,time) VALUES(%s,%s) ON DUPLICATE KEY UPDATE time=%s"""
sql_coin = """INSERT INTO coin(coin_short,coin_name,price_now,open_day,transaction_day,open_4hr,transaction_4hr,open_1hr,transaction_1hr)
 VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE price_now=%s,open_day=%s,transaction_day=%s,open_4hr=%s,transaction_4hr=%s,open_1hr=%s,transaction_1hr=%s"""
custom_headers = {
    'accept':'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
	'Accept-Encoding': 'gzip, deflate, br',
	'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
    'accept-language':'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7'
}
if __name__ == "__main__":
    lines = open('KRW.txt').readlines()
    now = time.localtime()
    s = "%04d-%02d-%02d %02d:%02d:%02d" % (
        now.tm_year, now.tm_mon, now.tm_mday, now.tm_hour, now.tm_min, now.tm_sec)
    curs.execute(sql_log, ('START',s,s))
    curs.execute(sql_del)
    conn.commit()
    idx = 1
    while True:
        print(idx,' 번째 추출중...')
        idx += 1
        for line in lines:
            name,short = line.split(',')
            name = name.strip()
            short = short.split('/')[0].strip()
            # 현재가
            try:
                while True:
                    try:
                        html = requests.get("https://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/3?code=CRIX.UPBIT.KRW-"+short+"&count=1&",headers=custom_headers )
                        jsonlists = json.loads(html.text)
                        price_now = jsonlists[0]['tradePrice']
                        break
                    except:
                        print("\t>>> 503 Error Locate 1 ", short)
                        time.sleep(0.3)
                time.sleep(0.2)
                #1일 오픈# 1일 거래금액
                while True:
                    try:
                        html = requests.get(
                            "https://crix-api-endpoint.upbit.com/v1/crix/candles/days?code=CRIX.UPBIT.KRW-"+short+"&count=1&",
                            headers=custom_headers)
                        jsonlists = json.loads(html.text)
                        open_day = jsonlists[0]['openingPrice']
                        transaction_day = jsonlists[0]['candleAccTradePrice']
                        break
                    except:
                        print("\t>>> 503 Error Locate 2 ", short)
                        time.sleep(0.3)
                time.sleep(0.2)
                # 4시간일 오픈, 거래금액
                while True:
                    try:
                        html = requests.get(
                            "https://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/240?code=CRIX.UPBIT.KRW-"+short+"&count=1&",
                            headers=custom_headers)
                        jsonlists = json.loads(html.text)
                        open_4hr = jsonlists[0]['openingPrice']
                        transaction_4hr = jsonlists[0]['candleAccTradePrice']
                        break
                    except:
                        print("\t>>> 503 Error Locate 3 ", short)
                        time.sleep(0.3)
                time.sleep(0.3)
                # 1시간일 오픈, 거래금액
                while True:
                    try:
                        html = requests.get(
                            "https://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/60?code=CRIX.UPBIT.KRW-"+short+"&count=1&",
                            headers=custom_headers)
                        jsonlists = json.loads(html.text)
                        open_1hr = jsonlists[0]['openingPrice']
                        transaction_1hr = jsonlists[0]['candleAccTradePrice']
                        break
                    except:
                        print("\t>>> 503 Error Locate 4 ", short)
                        time.sleep(0.3)
                curs.execute(sql_coin, (short,name,price_now,open_day,transaction_day,open_4hr,transaction_4hr,open_1hr,transaction_1hr,price_now,open_day,transaction_day,open_4hr,transaction_4hr,open_1hr,transaction_1hr))
            except:
                print("오류 : " ,short)
                print(html.text)
            now = time.localtime()
            s = "%04d-%02d-%02d %02d:%02d:%02d" % (
                now.tm_year, now.tm_mon, now.tm_mday, now.tm_hour, now.tm_min, now.tm_sec)
            curs.execute(sql_log, ('PARSING', s, s))
            conn.commit()
