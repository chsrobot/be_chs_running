# CHS RUNNING BACKEND
A brief description of the project.

# API Document
## Endpoints
``` 
https://running.chs.ac.th/api
``` 


### /auth/signup

Method: POST

Description: Sign Up User Account

Request Body:
``` 
{
  "name": "",
  "lastname": "",
  "email": "",
  "password": ""
}
``` 
Response Body: (If Success)

``` 
Status Code 201
{
  "message": "Created",
}
``` 
Response Body: (If User Exist)

``` 
Status Code 409
{
  "message": "Conflict",
}
``` 

### /auth/signin

Method: POST

Description: Sign In User Account

Request Body:
``` 
{
  "email": "",
  "password": ""
}
``` 
Response Body: (If Success)

``` 
Status Code 200
{
  "message": "Success",
  "name": "",
  "lastname": "",
  "email": "",
}
``` 
Response Body: (If Invalid Credentials)

``` 
Status Code 401
{
  "message": "Invalid Credentials",
}
``` 
Response Body: (If User Doesn't Exist)

``` 
Status Code 404
{
  "message": "User Not Found",
}
``` 

### /info/me

Method: POST

Description: Save User Information

Request Body:
``` 
{
  "gender": "",
  "type_run": "",
  "birthdate": "",
  "bibnumber": "",
  "phonenumber": "",
  "idcard": "",
  "healthproblems": "",
  "emergencyphone": "",
  "emergencontact": "",
  "relationship": "",
  "bloodtype": "",
  "email": "",
  "bib_number": ""
}
``` 
Response Body: (If First Time Create)
``` 
Status Code 201
{
  "message": "Created"
}
``` 
Response Body: (If Update Exist Data)
``` 
Status Code 200
{
  "message": "Updated"
}
``` 


### /info/me

Method: GET

Description: Save User Information

Request Body:
``` 
{
  "email": ""
}
``` 
Response Body: (If Data Exist)
``` 
Status Code 200
{
  "gender": "",
  "type_run": "",
  "birthdate": "",
  "bibnumber": "",
  "phonenumber": "",
  "idcard": "",
  "healthproblems": "",
  "emergencyphone": "",
  "emergencontact": "",
  "relationship": "",
  "bloodtype": "",
  "email": "",
  "bib_number": ""
}
``` 
Response Body: (If Data Doesn't Exist)

``` 
Status Code 404
{
  "message": "User Not Found",
}
``` 

### /info/friend

Method: POST

Description: Save Friend Information

Request Body:
``` 
{
  "name": "",
  "lastname": "",
  "gender": "",
  "type_run": "",
  "birthdate": "",
  "bibnumber": "",
  "phonenumber": "",
  "idcard": "",
  "healthproblems": "",
  "emergencyphone": "",
  "emergencontact": "",
  "relationship": "",
  "bloodtype": "",
  "email": "",
  "bib_number": ""
}
``` 
Response Body: (If First Time Create)
``` 
Status Code 201
{
  "message": "Created"
}
"data":
[
  {
  "gender": "",
  "type_run": "",
  "birthdate": "",
  "bibnumber": "",
  "phonenumber": "",
  "idcard": "",
  "healthproblems": "",
  "emergencyphone": "",
  "emergencontact": "",
  "relationship": "",
  "bloodtype": "",
  "email": "",
  "bib_number": ""
}
]
``` 

### /info/friend

Method: GET

Description: Get Friend (List) Information

Request Body:
``` 
{
  "email": ""
}
``` 
Response Body: (If Data Exist)
``` 
Status Code 200
"message": "Data found",
"data":
[
  {
  "gender": "",
  "type_run": "",
  "birthdate": "",
  "bibnumber": "",
  "phonenumber": "",
  "idcard": "",
  "healthproblems": "",
  "emergencyphone": "",
  "emergencontact": "",
  "relationship": "",
  "bloodtype": "",
  "email": "",
  "bib_number": ""
}
]
``` 
Response Body: (If Data Doesn't Exist)

``` 
Status Code 404
{
  "message": "User Not Found",
}
``` 



### /info/friend

Method: DELETE

Description: Get Friend (List) Information

Request Body:
``` 
{
  "email": "",
  "idcard":""
}
``` 
Response Body: (If Data Exist)
``` 
Status Code 200
"data":
{
    "message": "Data deleted"
}
``` 
Response Body: (If Data Doesn't Exist)

``` 
Status Code 404
{
  "message" : "Data not found"
}
```

### /product/
Method: POST

Description: Save Product

``` 
{
  "type_hat": "",
  "type_shirt": "",
  "price_hat": "",
  "price_shirt": ""
}
``` 
Response Body: (If First Time Create)
``` 
Status Code 201
{
  "message": "Created"
}
``` 
Response Body: (If Update Exist Data)
``` 
Status Code 200
{
  "message": "Updated"
}
``` 

### /product/
Method: GET

Description: Get product 

Request Body:
``` 
{
  "id": ""
}
``` 
Response Body: (If Data Exist)
``` 
Status Code 200
"message": "Data found",
"data":
[
  {
  "type_hat": "",
  "type_shirt": "",
  "price_hat": "",
  "price_shirt": ""
}
]
``` 
Response Body: (If Data Doesn't Exist)

``` 
Status Code 404
{
  "message": " Not Found Product",
}
```