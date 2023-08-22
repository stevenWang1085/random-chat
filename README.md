## 專案介紹

- 線上即時聊天配對系統，運行於docker
- 聊天資料儲存於Redis，透過排程定期撈資料存於資料庫  
- 自架websockets服務  
- API使用JWT驗證
- 功能如下：
    - 註冊、登入、Google登入、登出
    - 隨機配對聊天
    - 加入好友
    
## 運行環境需求

- 前端 ： vue3
- 後端 ： Laravel9、PHP8.2、MySQL、Redis
- 服務 ： Docker、Docker-Compose

## 服務介紹

- 目前線上系統架設於GCP VM中
- 採用LoadBalance防止大量流量湧入造成的崩潰
- https協定 
- [系統網址](https://chat-chat.tw)
