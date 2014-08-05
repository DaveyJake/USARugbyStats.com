
# Create the list of files
css = https://raw.github.com/harvesthq/chosen/3640fa177816aee932aaeb402a28c063c11f52da/chosen/chosen.css \
      https://raw.github.com/harvesthq/chosen/3640fa177816aee932aaeb402a28c063c11f52da/chosen/chosen-sprite.png \
      http://www.sportsdb.org/modules/sm/assets/downloads/sportsml.css \
      https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css

js = https://raw.github.com/AllPlayers/chosen/efadee3349a7b4d85b75fff30c0586b90275fff2/chosen/chosen.jquery.min.js \
     https://raw.githubusercontent.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js \
     http://momentjs.com/downloads/moment.js

all: deleteall makecss makejs

deleteall: makedeletecss makedeletejs

makecss:
	homedir=`pwd`; mkdir public/assets/css/vendor; cd public/assets/css/vendor; wget ${css}; cd ${homedir};

makejs:
	homedir=`pwd`; mkdir public/assets/js/vendor; cd public/assets/js/vendor; wget ${js}; cd ${homedir};

makedeletecss:
	rm -rf public/assets/css/vendor;

makedeletejs:
	rm -rf public/assets/js/vendor;
