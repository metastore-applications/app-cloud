<?php use MetaStore\App\Kernel\Route;
use MetaStore\App\Cloud\Config; ?>

<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head prefix="og: http://ogp.me/ns#">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="copyright" content="METADATA / FOUNDATION" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php echo Config\General::getSystem( 'name' ) ?></title>

	<!-- open graph -->
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="<?php echo Config\General::getSystem( 'name' ) ?>" />
	<meta property="og:title" content="<?php echo Config\General::getSystem( 'name' ) ?>" />
	<meta property="og:description" content="" />
	<meta property="og:image" content="" />
	<meta property="og:url" content="<?php echo Route::HTTP_HOST() ?>" />
	<!-- / open graph -->

	<!-- twitter -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo Config\General::getSystem( 'name' ) ?>" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:creator" content="" />
	<!-- / twitter -->

	<!-- styles -->
	<link type="text/css" rel="stylesheet" href="/vendor/metastore/lib-fontawesome-free/css/all.min.css" />
	<link type="text/css" rel="stylesheet" href="/vendor/metastore/lib-bulma/css/bulma.min.css" />
	<link type="text/css" rel="stylesheet" href="/resources/assets/styles/theme.css" />
	<!-- / styles -->

	<!-- favicon -->
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
	<!-- / favicon -->
</head>
<body itemscope itemtype="http://schema.org/WebPage">
