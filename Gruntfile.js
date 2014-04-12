module.exports = function (grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['Gruntfile.js', './js/**/*.js'],
        tasks: ['jshint'],
        options: {
          livereload: true
        }
      },
      styles: {
        files: ['./less/*.less'],
        tasks: ['less']
      }
    },
    less: {
      compile: {
        files: [
          {
            "./css/styles.css": "./less/styles.less"
          }
        ]
      }    
    },
    jshint: {
      files: ['Gruntfile.js', './js/**/*.js'],
      options: {
        ignores: ['./js/vendor/*.js', './js/multiselect/multiselect.js', './js/jnotify/*.js']
      }
    },
    // uglify: {
    //   options: {
    //     banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
    //   },
    //   build: {
    //     src: 'src/<%= pkg.name %>.js',
    //     dest: 'build/<%= pkg.name %>.min.js'
    //   }
    // }
  });
  
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-less');

  grunt.registerTask('default', ['watch']);

};