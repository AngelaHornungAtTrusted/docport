File Paths

https://developer.wordpress.org/plugins/plugin-basics/determining-plugin-and-content-directories/
 * Couple examples below, much more on page
 * plugins_url( 'myscript.js', __FILE__ ); //returns dynamic path for myscript.js
 * wp_enqueue_script() //to embed script with dynamic url
 * wp_enqueue_style() //to embed css with dynamic url

 home_url()	        Home URL	http://www.example.com
 site_url()	        Site directory URL	http://www.example.com or http://www.example.com/wordpress
 admin_url()	    Admin directory URL	http://www.example.com/wp-admin
 wp_upload_dir()	Upload directory URL (returns an array)	http://www.example.com/wp-content/uploads