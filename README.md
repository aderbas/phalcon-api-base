Phalcon API Sample.

To test you need to have installed Phalcon. See more about it [here](https://phalconphp.com/en/download) 

I recommend that you create a site in Apache or Nginx to point to the folder you made the clone, or use localhost.

- Using curl
```
$ curl -i -X POST -d '{"email":"aderbal@aderbalnunes.com", "pwd":"123456"}' 
  http://localhost/phalcon-api-base/auth
```
Response: (token created if success)
```
HTTP/1.1 200 OK
Date: Tue, 12 Apr 2016 14:54:00 GMT
Server: Apache/2.4.7 (Ubuntu)
X-Powered-By: PHP/5.5.9-1ubuntu4.14
Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE
Access-Control-Allow-Headers: Content-Type, Accept, SOAPAction, Origin, Authorization
Content-Length: 211
Content-Type: application/json

{"token":"xyz...."}
```
Before received token try (replace 'xyz' for received token)
```
$ curl -i -X GET -H "Authorization: Bearer xyz" http://localhost/phalcon-api-base/user
```
Response: (fake [user controller] (https://github.com/aderbas/phalcon-api-base/blob/master/controller/UserController.php))
```
HTTP/1.1 200 OK
Date: Tue, 12 Apr 2016 15:01:06 GMT
Server: Apache/2.4.7 (Ubuntu)
Vary: Authorization
X-Powered-By: PHP/5.5.9-1ubuntu4.14
Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE
Access-Control-Allow-Headers: Content-Type, Accept, SOAPAction, Origin, Authorization
Content-Length: 144
Content-Type: application/json

{"result":[{"name":"Tiago","email":"tiago@domain.com","id":213},{"name":"Bal","email":"bal@domain.com","id":123}],"msg":"Data Result","error":0}
```
- Using Postgres or Mysql

The database information is in <code>config/config.ini</code>. Change adapter Postgresql/Mysql and access information. The queries are in controllers. Learn more about it [here](https://docs.phalconphp.com/en/latest/reference/controllers.html). Remember that our controller will always show our result. <code>Util::printResult(...)</code>

- Routes

All routes are in collections. Just create a file similar to the model (<code>collection/UserCollection.php</code>).
