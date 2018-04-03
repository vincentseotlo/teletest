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
	VERB	URL
	POST 	http://127.0.0.1:8000/api/dequeue/append?input=<value>
	POST	http://127.0.0.1:8000/api/dequeue/prepend?input=<value>
	DELETE	http://127.0.0.1:8000/api/dequeue/pop
	DELETE	http://127.0.0.1:8000/api/dequeue/eject
	GET	http://127.0.0.1:8000/api/dequeue/show
	GET	http://127.0.0.1:8000/api/dequeue/show?sort=asc
	GET	http://127.0.0.1:8000/api/dequeue/show?sort=desc
