(function () {
    'use strict';
    var Weather, Pictures;
    Weather = {
        url: 'http://api.openweathermap.org/data/2.5/weather?id=5128581&units=imperial',
        data: {},
        promise: undefined,
        get: function () {
            var self = this;
            self.promise = $.ajax({
                url: self.url,
                method: 'GET'
            });

            self.promise.done(function (data) {
                self.data = data;
            });
        }
    };
    Weather.get();
    Weather.promise.done(function (data) {
        var temp, clouds;
        temp = data.main.temp;
        clouds = data.weather[0].description;
        $('#temp').html(temp + ' F');
        $('#clouds').html(clouds);
    });

    Pictures = {
        Imgur: {
            client_id: '4bb78b0ee127b43',
            url: 'https://api.imgur.com/3/image/',
            id: "",
            promise: {},
            width: 0,
            height: 0,
            check: false,
            image: "",
            status: true,
            get: function () {
                var self = this, image, domain, id, index, chosen, url, x = true;
                index = Pictures.pick(Pictures.data.length);
                chosen = Pictures.data[index];
                if(chosen === undefined) {
                    self.get();
                }
                image = chosen.data.url;
                domain = chosen.data.domain;
                this.id = Pictures.parse(image, domain);
                if(this.id === false) {
                    self.get();
                }
                url = "https://api.imgur.com/3/image/" + this.id;
                self.promise = $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Client-ID ' + self.client_id
                    }
                });

                self.promise.done(function (data) {
                    self.image = data.data.link;
                    self.width = data.data.width;
                    self.height = data.data.height;
                    if(self.width<1920) {
                        self.get();
                    } else {
                        console.log(self.image);
                        Pictures.setImage(self.image);
                    }
                });
            }
        },
        pick: function (length) {
            return Math.floor(Math.random() * length - 1);
        },
        url: 'http://www.reddit.com/r/wallpaper+wallpapers+bigwallpapers/top.json?limit=100',
        data: {},
        promise: undefined,
        setImage: function (url) {
            $('body').css("background-image", "url('" + url + "')");
            $('body').css("background-image-size", 'cover');
        },
        parse: function (url, domain) {
            var parser = document.createElement('a'),
                paths,
                more;
            parser.href = url;
            if (domain === "imgur.com") {
                paths = parser.pathname.split('/');
                return paths[paths.length - 1];
            }
            if (domain === "i.imgur.com") {
                paths = parser.pathname.split('/');
                more = paths[paths.length - 1].split('.');
                return more[0];
            }
            return false;
        },
        get: function () {
            var self = this;
            self.promise = $.ajax({
                url: self.url,
                method: "GET"
            });
            self.promise.done(function (data) {
                self.data = data.data.children;
                self.Imgur.get();
            });

            return self.promise;
        }
    };
    Pictures.get();
    setInterval(function (){
        Pictures.get();
    }, 30000);
}());