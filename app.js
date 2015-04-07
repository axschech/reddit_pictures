(function() {
	
	var Weather = {
		url: 'http://api.openweathermap.org/data/2.5/weather?id=5128581&units=imperial',
		data: {},
		promise: undefined,
		get: function() {
			var self = this;
			self.promise = $.ajax({
				url: self.url,
				method: 'GET'
			});

			self.promise.done(function (data) {
				self.data = data;
			});
		}
	}
	// Weather.get();
	// Weather.promise.done(function (){
	// 	console.log(Weather.data);
	// });

	var Pictures = {
		Imgur: {
			client_id: '4bb78b0ee127b43',
			url: 'https://api.imgur.com/3/image/',
			id: "",
			promise: {},
			get: function () {
				var url = "https://api.imgur.com/3/image/"+this.id
				, self = this;
				self.promise = $.ajax({
					url: url,
					method: 'GET',
					headers: {
						'Authorization': 'Client-ID ' + self.client_id
					}
				});

				self.promise.done(function (data) {
					console.log(data);
				});
			}
		},
		url: 'http://www.reddit.com/r/wallpaper+wallpapers+bigwallpapers/top.json',
		data: {},
		promise: undefined,
		parse: function (url, domain) {
			var parser = document.createElement('a');
			parser.href = url;
			if (domain === "imgur.com") {
				var paths = parser.pathname.split('/');
				return paths[paths.length-1];
			} else if(domain === "i.imgur.com") {
				var paths, more; 
				paths = parser.pathname.split('/');
				more = paths[paths.length-1].split('.');
				return more[0];
			} else {
				return false;
			}
			
		},
		get: function () {
			var self = this;
			self.promise = $.ajax({
				url: self.url,
				method: "GET"
			});
			self.promise.done(function (data) {
				self.data = data.data.children;
				var image, domain, id;
				image = self.data[19].data.url;
				console.log(image);
				domain = self.data[19].data.domain;
				id = self.parse(image, domain);
				self.Imgur.id = id;
				self.Imgur.get();
			})
		}
	}

	Pictures.get();

})()