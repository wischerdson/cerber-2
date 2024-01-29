# Авторизация и аутентификация

## Терминология

**Access-токен (токен доступа)** - JWT-токен (стандарт [RFC 7519](https://tools.ietf.org/html/rfc7519)), используемый для подтверждения личности пользователя. Передается при запросе в HTTP-заголовке **Authorization** типа **Bearer**. Многоразовый, но имеет короткий срок жизни. 

**Refresh-токен (токен обновления)** - JWT-токен (стандарт [RFC 7519](https://tools.ietf.org/html/rfc7519)), позволяющий пользователям запрашивать новые access-токены по истечении их времени жизни. Одноразовый, но имеет более длительный срок жизни (в отличие от access-токена)

**Пара токенов** - access-токен и refresh-token

**Грант (grant)** - набор данных, позволяющий выполнить обмен на пару токенов.

**Грант тайп (grant type)** - функционал, способный выпускать пару токенов. Грант тайп обязан проверить переданные от пользователя данные и принять решение о выпуске пары токенов.

**Сеанс/сессия авторизации (auth session)** - запись о входе пользователя в систему. Все действия пользователя выполняются в рамках сессии.<br>*Например пользователь вошел в систему через компьютер и через смартфон. В этом случае у пользователя есть 2 сеанса авторизации.*.

**Гард (guard)** (*из терминологии Laravel*) - функционал, 
проверяющий личность пользователя при каждом запросе. [Аутентификация в Laravel](https://laravel.com/docs/10.x/authentication).

## Auth flow

1. Создание пользователя и гранта для него (например через artisan-команду **app:admin**).
2. Создание сессии авторизации с парой токенов доступа, пример:<br>
```http
POST /auth/access-token HTTP/1.1
Content-Type: application/json
Accept: application/json
User-Agent: PostmanRuntime/7.36.1

{
	"grant_type": "password",
	"login": "admin",
	"password": 123123
}
```
Ответ:
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
    "token_type": "Bearer",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDY1NTg4MDgsImV4cCI6MTcwNjU2MDYwOCwic2VzIjoyfQ.vPHTu3K_fj7ty3yZYXJAjhPXbn42ryk1peIGdV65sek",
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDY1NTg4MDgsImV4cCI6MTcwOTIzNzIwOCwiaWQiOjIsImNvZGUiOiJiYmEiLCJzZXMiOjJ9.1KDeuyuyfdvJIfw8CHS4sHyyrmXquRXplBRaFTj29_4"
}
```
