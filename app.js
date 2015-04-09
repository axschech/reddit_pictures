
//               __   ___ __    ___  _     __
//   _______ ___/ /__/ (_) /_  / _ \(_)___/ /___ _________ ___
//  / __/ -_) _  / _  / / __/ / ___/ / __/ __/ // / __/ -_|_-<
// /_/  \__/\_,_/\_,_/_/\__/ /_/  /_/\__/\__/\_,_/_/  \__/___/

/**
 *  @description Loads images from reddit and gets weather from OpenWeatherMap
 *  @author  Alex Schechter
 *  @version 0.1
 *  Notes:
 *  Pictures.get() -> Pictures.Imgur.get() -> Pictures.setImage();
 *  Load reddit data -> check picture from Imgur -> set image source
 */

(function () {
    'use strict';
    var Weather,
        Pictures,
        Geolocation,
        Fullscreen;

    Fullscreen = {
        toggleFullScreen: function() {
            if (!document.fullscreenElement &&    // alternative standard method
                    !document.mozFullScreenElement &&
                        !document.webkitFullscreenElement &&
                            !document.msFullscreenElement ) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                  document.documentElement.requestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) {
                  document.documentElement.msRequestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                  document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                  document.documentElement.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                  document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
                }
            }
        },
        clicked: function (e) {
            // e.preventDefault();
            this.toggleFullScreen();
        }
    };

    Geolocation = {
        promise: undefined,
        position: undefined,
        get: function () {
            var deferred = $.Deferred();
            navigator.geolocation.getCurrentPosition(deferred.resolve, deferred.reject);
            this.promise = deferred;
        }
    };

    Weather = {
        url: 'http://api.openweathermap.org/data/2.5/weather?id=5128581&units=imperial',
        data: {},
        promise: undefined,
        get: function () {
            var url,
                coords,
                self = this;
            console.log(Geolocation.position);
            if(Geolocation.position === undefined) {
                url = self.url;
            } else {
                coords = Geolocation.position;
                url = 'http://api.openweathermap.org/data/2.5/weather?units=imperial&';
                url += 'lat=' + coords.latitude;
                url += "&lon=" + coords.longitude;
            }
            console.log(url);
            self.promise = $.ajax({
                url: url,
                method: 'GET'
            });

            self.promise.done(function (data) {
                self.data = data;
            }).fail(function () {
                self.get();
                return;
            });
        }
    };

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
            title: "",
            get: function () {
                var self = this,
                    image,
                    domain,
                    index,
                    chosen,
                    subreddit,
                    url;
                index = Pictures.pick(Pictures.data.length);
                chosen = Pictures.data[index];
                if(chosen === undefined) {
                    self.get();
                    return;
                }
                subreddit = chosen.data.subreddit;
                image = chosen.data.url;
                domain = chosen.data.domain;
                self.title = chosen.data.title;
                this.id = Pictures.parse(
                    image,
                    domain,
                    subreddit
                );
                if(this.id === false) {
                    self.get();
                    return;
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
                    console.log(self.width);
                    if(self.width<1920) {
                        self.get();
                        return;
                    } else {
                        console.log(self.image);
                        Pictures.setImage(
                            self.image,
                            self.title,
                            subreddit
                        );
                    }
                });

                self.promise.fail(function (data) {
                    Pictures.get();
                });
            }
        },
        pick: function (length) {
            return Math.floor(Math.random() * length - 1);
        },
        current: undefined,
        options: [
            'new',
            'new',
            'new',
            'top',
            'hot'
        ],
        url: function () {
            if (this.current === undefined) {
                this.current = this.options[0];
            }
            return 'http://www.reddit.com/user/axschech/m/sfwporn/' + this.current + '.json?limit=1000';
        },
        data: {},
        promise: undefined,
        subreddit: "",
        setImage: function (url, title, subreddit) {
            var link = "<a href='http://reddit.com/r/" + subreddit + "'>" + subreddit + "</a>";
            link += "<br /><small id='pic_title'></small>";
            $('body').css("background-image", "url('" + url + "')");
            $('body').css("background-size", 'cover');
            $('body').css("background-repeat", "no-repeat");
            $('body').css('width','100%');
            $('body').css('min-height', '100%');
            var html = '<a target="_blank" href="' + url + '">' + title + "</a>";
            $('#subreddit').html(link);
            $('#pic_title').html(html);
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
                url: self.url(),
                method: "GET"
            });
            self.promise.done(function (data) {
                var index = self.options.indexOf(self.current);
                if (index == self.options.length - 1) {
                    self.current = self.options[0];
                } else {
                    self.current = self.options[index + 1];
                }
                self.data = data.data.children;
                self.Imgur.get();
            });

            return self.promise;
        }
    };
    $(document).ready(function () {
        $('#fullscreen').on('click', function (e) {
            // console.log('hey');
            Fullscreen.clicked(e);
            $(window).trigger('resize');
        });
    });

    Geolocation.get();
    Geolocation.promise.always(function (data) {
        Geolocation.position = data.coords
        Weather.get();
        Weather.promise.done(function (data) {
            var temp, clouds, place;
            temp = data.main.temp;
            place = data.name;
            clouds = data.weather[0].description;
            place = "<small>Weather in</small> " + data.name;
            $('#temp').html(temp + ' F');
            $('#place').html(place);
            $('#clouds').html(clouds);
        });
    });

    Pictures.get();
    setInterval(function (){
        Pictures.get();
    }, 30000);
}());