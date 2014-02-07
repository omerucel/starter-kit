module.exports = function(grunt){
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'style/app.css': 'sass/app.scss'
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: 'sass/*.scss',
                tasks: ['sass']
            }
        },
        connect: {
            server: {
                options: {
                    port: 9001
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-connect');

    grunt.registerTask('default', ['connect', 'watch']);
};