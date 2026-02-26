// GULP PACKAGES
var gulp  = require('gulp'),
    browserSync = require('browser-sync').create();

var url = 'http://localhost/wp-empty/';

//hlavní task
gulp.task('dev', function() {

    browserSync.init({

		files: [{

			match: [

				//php files
				'./**//**.php', 
				//style.css
				//"style.css",
				// css files 
				//"assets/css/**//**.css",
				//javascript files
				//"assets/js/**//**.js"

			],
			fn:  function (event, file) {
				this.reload()
			}

		}],
		proxy: url, 
		port: 3000,
		injectChanges: true 
	});

});