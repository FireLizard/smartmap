module.exports = function(grunt) {

    grunt.initConfig({

        bower: {
            install: {
                dest: './src/Resources/Public/vendor/',
                options: {
                    expand: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bower');
};
