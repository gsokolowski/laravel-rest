## Simple TODO REST in laravel

Auth on JWT

REST Request example

POSTMAN

If you want to test the authentication API now you can do that by using Postman and passing  email and a password field as content of the request for the login after posting a first request to the register endpoint with also the name field. If all is made right the register request will return the created user and the authenticate request will return a json object with a single field token

To register new user
POST 
http://localhost:8000/api/register?name=Nina&email=nina@gmail.com&password=abc1234

Returns registered user
{"name":"Mona","email":"mona2@gmail.com","updated_at":"2016-02-21 18:32:27","created_at":"2016-02-21 18:32:27","id":5}

To get jwt-token for registered user
POST
http://localhost:8000/api/authenticate?email=nina@gmail.com&password=abc1234

Returns token
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL2F1dGhlbnRpY2F0ZSIsImlhdCI6MTQ3Mjk1MDE4NSwiZXhwIjoxNDcyOTUzNzg1LCJuYmYiOjE0NzI5NTAxODUsImp0aSI6IjA3ODRhMmI4ZWNiYzhkNWQzMmM2YTM4ZDIzMGI3M2VhIn0.fZCiiig_3e9R8J-dlLFQz4O2eTEOwrz6iNL-Y1wRMvM"
}


To samo nie zadziala. Tu jest call do authenticate() method i ona zostanie wywolana
GET
http://localhost:8000/api/authenticate/user?id=4  - To samo nie zadziala.

Zeby dostac wszystkie todos dla tego uzytkownika to Wolasz to z POSTMANA tak
http://localhost:8000/api/todo?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL2F1dGhlbnRpY2F0ZSIsImlhdCI6MTQ3Mjk1MDE4NSwiZXhwIjoxNDcyOTUzNzg1LCJuYmYiOjE0NzI5NTAxODUsImp0aSI6IjA3ODRhMmI4ZWNiYzhkNWQzMmM2YTM4ZDIzMGI3M2VhIn0.fZCiiig_3e9R8J-dlLFQz4O2eTEOwrz6iNL-Y1wRMvM

Token musi byc podany
I dostajesz jako json
[
  {
    "id": 5,
    "description": "another one",
    "is_done": 0,
    "owner_id": 4,
    "created_at": "2016-02-22 00:00:00",
    "updated_at": "2016-02-22 00:00:00"
  }
]
