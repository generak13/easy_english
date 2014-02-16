module.exports = function (grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['Gruntfile.js', './src/**/*.js'],
        tasks: ['jshint'],
        options: {
          livereload: true
        }
      },
      styles: {
        files: ['./src/less/*.less'],
        tasks: ['less']
      }
    },
    less: {
      compile: {
        files: [
          {
            "./src/css/styles.css": "./src/less/styles.less"
          },
          {
            "../css/styles.css": "./src/less/styles.less"
          }
        ]
      }    
    },
    jshint: {
      files: ['Gruntfile.js', './src/**/*.js'],
      options: {
        ignores: ['./src/js/vendor/*.js']
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
