
# Create the list of files
css = https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/css/bootstrap.min.css \
	  https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/css/bootstrap-theme.min.css \
	  https://cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css \
	  https//cdn.jsdelivr.net/chosen/1.1.0/chosen-sprite.png \
	  https//cdn.jsdelivr.net/chosen/1.1.0/chosen-sprite@2x.png \
	  https://raw.github.com/harvesthq/chosen/3640fa177816aee932aaeb402a28c063c11f52da/chosen/chosen-sprite.png \
	  https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css

twbsfont = https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.eot \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.svc \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.ttf \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.woff

js = https://raw.githubusercontent.com/jquery/jquery/0d5ec2d8ac94a419ee47a39319c43ff9a7326b50/dist/jquery.min.js \
	 https://raw.githubusercontent.com/jquery/jquery/0d5ec2d8ac94a419ee47a39319c43ff9a7326b50/dist/jquery.min.map \
	 https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/js/bootstrap.min.js \
	 https://cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js \
	 https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js \
	 http://momentjs.com/downloads/moment.js \
         https://ajax.googleapis.com/ajax/libs/angularjs/1.2.22/angular.min.js \
         https://ajax.googleapis.com/ajax/libs/angularjs/1.2.22/angular.min.js.map \
         https://github.com/rubenv/angular-encode-uri/raw/master/dist/angular-encode-uri.min.js

all: deleteall makecss maketwbsfont makejs

deleteall: makedeletecss makedeletejs makedeletetwbsfont

makecss:
	mkdir public/assets/css/vendor; cd public/assets/css/vendor; wget ${css}; cd -;

maketwbsfont:
	mkdir public/assets/css/fonts; cd public/assets/css/fonts; wget ${twbsfont}; cd -;

makejs:
	mkdir public/assets/js/vendor; cd public/assets/js/vendor; wget ${js}; cd -;

makedeletecss:
	rm -rf public/assets/css/vendor;

makedeletetwbsfont:
	rm -rf public/assets/css/fonts;

makedeletejs:
	rm -rf public/assets/js/vendor;
