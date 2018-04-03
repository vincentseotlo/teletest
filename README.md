# tf

HOWTO RUN

1. install 
1.1 laravel as detailed on https://laravel.com/docs/5.6
1.2 download Postman from https://www.getpostman.com/apps, and unarchive in your favourite folder

2. clone teletest on github
# git clone https://github.com/vincentseotlo/teletest.git

3. change directory to teletest and launch the app via the following
# php artisan serve 

4. go into the postman folder and run 
# Postman

5. Here are valid urls to manipulate the queue
5.1 POST 	http://127.0.0.1:8000/api/dequeue/append?input=<value>
5.2 POST	http://127.0.0.1:8000/api/dequeue/prepend?input=<value>
5.3 DELETE	http://127.0.0.1:8000/api/dequeue/pop
5.4 DELETE	http://127.0.0.1:8000/api/dequeue/eject
5.5 GET	http://127.0.0.1:8000/api/dequeue/show
5.6 GET	http://127.0.0.1:8000/api/dequeue/show?sort=asc
5.7 GET	http://127.0.0.1:8000/api/dequeue/show?sort=desc
