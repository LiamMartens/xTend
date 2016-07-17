![Banner](https://xtend.liammartens.com/img/banner.svg)

#Introducing xTend

###What is xTend?
xTend is a PHP MVC framework. While other frameworks, such as Laravel, are focusing on implementing every feature or function a developer could ever want, xTend is focused on keeping it fast, adaptable, extendable and non-bloated. This means there is a lot of built-in features you'll find in Laravel you will not find here, however because of the adaptable and extendable nature of xTend it is extremely easy to add a library, or your own script, to enable a certain feature.

###What's in it for me?
* Dependency free
* Simple packagist support
* Clean and powerful URL's
* Easy to understand structure (Model-View-Controller)
* Easy to setup and configure
* Easy to adapt and extend
* Blazing fast
* Easily customizable templating engine
* A lot of helper functions to keep your code clean
* A simple CLI to do basic interactions

###Benchmark
####My setup
HP Envy 15-j000eb  
Elementary OS Freya 0.3.2  
Intel i7-4700MQ  
12 GB RAM  
1 TB HDD (No SSD)  

####Webserver
Hiawatha webserver with PHP7 FPM  
[Link](https://www.hiawatha-webserver.org/)  

####The command
`ab -c 10 -t 10 http://localhost/`

####The results right after setup
1st run
```
    Complete requests:      23136
    Requests per second:    2313.58 [#/sec] (mean)
    Time per request:       4.322 [ms] (mean)
    Time per request:       0.432 [ms] (mean, across all concurrent requests)
```

2nd run
```
    Complete requests:      20817
    Requests per second:    2081.59 [#/sec] (mean)
    Time per request:       4.804 [ms] (mean)
    Time per request:       0.480 [ms] (mean, across all concurrent requests)
```

####The results with view, layout, controller and URL parameter
1st run
```
    Complete requests:      18934
    Requests per second:    1893.25 [#/sec] (mean)
    Time per request:       5.282 [ms] (mean)
    Time per request:       0.528 [ms] (mean, across all concurrent requests)
```

2nd run
```
    Complete requests:      23163
    Requests per second:    2316.17 [#/sec] (mean)
    Time per request:       4.317 [ms] (mean)
    Time per request:       0.432 [ms] (mean, across all concurrent requests)
```

###Example
You can find an example application in the `examples` directory. It's a very simple tasklist project. (Don't forget to set the app url when you run it)   
<br>

###Documentation
[Read The Docs](http://xtend.readthedocs.org/en/latest/)  
<br>

###I got some questions
You can always send me a message on either [gitter](http://gitter.im) or using [email](mailto:hi@liammartens.com)
<br><br>
[Website](http://xtend.liammartens.com)
