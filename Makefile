
# Create the list of files
css = https://raw.github.com/eternicode/bootstrap-datepicker/99b6f8d3608c948a0c05c8e2e5ec3c4e2c1f96b2/css/datepicker.css\
      https://raw.github.com/harvesthq/chosen/3640fa177816aee932aaeb402a28c063c11f52da/chosen/chosen.css\
      https://raw.github.com/harvesthq/chosen/3640fa177816aee932aaeb402a28c063c11f52da/chosen/chosen-sprite.png\
      http://www.sportsdb.org/modules/sm/assets/downloads/sportsml.css

js = https://raw.github.com/eternicode/bootstrap-datepicker/99b6f8d3608c948a0c05c8e2e5ec3c4e2c1f96b2/js/bootstrap-datepicker.js\
     https://raw.github.com/kbwood/timeentry/3e1881d041f5ce0f8f338ea45ff4fe8e6e7f1a2d/jquery.timeentry.pack.js\
     https://raw.github.com/AllPlayers/chosen/efadee3349a7b4d85b75fff30c0586b90275fff2/chosen/chosen.jquery.min.js \
     https://raw.github.com/zuzara/jQuery-OAuth-Popup/7af47b7ddf867f11701cd642453071d080857df4/src/jquery.oauthpopup.js

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
