
# Create the list of files
css = https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/css/bootstrap.min.css \
	  https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/css/bootstrap-theme.min.css \
	  https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css \
	  https://github.com/ivaynberg/select2/raw/master/select2x2.png \
	  https://github.com/ivaynberg/select2/raw/master/select2.png \
	  https://github.com/ivaynberg/select2/raw/master/select2-spinner.gif \
	  https://github.com/ivaynberg/select2/raw/master/select2.css \
	  https://raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css

twbsfont = https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.eot \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.svc \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.ttf \
	   https://github.com/twbs/bootstrap/raw/master/dist/fonts/glyphicons-halflings-regular.woff

js = https://raw.githubusercontent.com/jquery/jquery/0d5ec2d8ac94a419ee47a39319c43ff9a7326b50/dist/jquery.min.js \
	 https://raw.githubusercontent.com/jquery/jquery/0d5ec2d8ac94a419ee47a39319c43ff9a7326b50/dist/jquery.min.map \
	 https://raw.githubusercontent.com/twbs/bootstrap/c068162161154a4b85110ea1e7dd3d7897ce2b72/dist/js/bootstrap.min.js \
	 https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js \
	 http://momentjs.com/downloads/moment.js \
     https://ajax.googleapis.com/ajax/libs/angularjs/1.2.22/angular.min.js \
     https://ajax.googleapis.com/ajax/libs/angularjs/1.2.22/angular.min.js.map \
     https://github.com/rubenv/angular-encode-uri/raw/master/dist/angular-encode-uri.min.js \
	 https://raw.githubusercontent.com/resin-io/ngRange/master/dist/ngRange.js \
         https://github.com/fmquaglia/ngOrderObjectBy/raw/master/src/ng-order-object-by.js \
	 http://cdn.jsdelivr.net/jquery.geocomplete/1.5.1/jquery.geocomplete.min.js \
	 https://github.com/ivaynberg/select2/raw/master/select2.js

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
