<?php
/**
 * Header
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Set up Meta -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
	<!-- Add Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

 <div class="preloader">
	<div class="preloader__icon"></div>
</div>

<!-- BEGIN of header -->
<header class="header" >

	<div class=" ">


        <div class="head-menu">

            <?php show_custom_logo(); ?>

            <nav class="navbar navbar-expand-md">
                <div class="navbar-header">

                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#main-menu-links" aria-expanded="false"></button>

                </div>

                <?php
                if ( has_nav_menu( 'header-menu' ) ) {
                    wp_nav_menu( array(
                        'menu'            => 'primary',
                        'theme_location'  => 'header-menu',
                        'container'       => 'div',
                        'container_class' => 'collapse navbar-collapse',
                        'container_id'    => 'main-menu-links',
                        'menu_class'      => 'navbar-nav header-menu',
                        'fallback_cb'     => 'Bootstrap_Navigation::fallback',
                        'walker'          => new Bootstrap_Navigation()
                    ) );
                }
                ?>
            </nav>
        </div>




</header>
<!-- END of header -->
