<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>   
   <?php include '/var/www/moksy.com/public_html/includes/preview/preview-meta.php'; ?>
   <?php include '/var/www/moksy.com/public_html/includes/preview/preview-styles.php'; ?>
         <script type='application/ld+json'>
         {
           "@context": "http://www.schema.org",
           "@type": "ProfessionalService",
           "name": "<?php echo $projectNickname; ?>",
           "title": "<?php echo $metaTitle; ?>",
           "url": "<?php echo $baseLink; ?>",
           "sameAs": [
              ""
           ],
           "logo": "<?php echo $logo; ?>",
           "priceRange": "$$$",
           "image": "<?php echo $metaImage; ?>",
           "description": "<?php echo $metaDescription; ?>",
           "address": {
              "@type": "PostalAddress",
              "streetAddress": "3-5, Marco Polo House, Lansdowne Road",
              "addressLocality": "London",
              "addressRegion": "Surrey",
              "postalCode": "CR0 2BX",
              "addressCountry": "United Kingdom"
           },
           "email": "support@moksy.com",
           "geo": {
              "@type": "GeoCoordinates",
              "latitude": "51.37683",
              "longitude": "-0.09728"
           },
           
           "openingHours": "Mo 09:00-17:00 Tu 09:00-17:00 We 09:00-17:00 Th 09:00-17:00 Fr 09:00-17:00",
           "telephone": "+44 0808 175 1749",
           "aggregateRating": {
                 "@type": "AggregateRating",
                 "ratingValue": "<?php echo $rating; ?>",
                 "ratingCount": "<?php echo $reviews; ?>"
               },
          "datePublished": "<?php echo $publishDate; ?>"
         }
         </script>

</head>
<body> 
<div itemprop="isPartOf" itemscope itemtype="http://schema.org/WebSite"></div>
<?php

try {
    // Use function to include files from base directory
    foreach ($pageBuild as $key => $value) {
         include($baseDir.$value);
    }
   
    // Include additional files conditionally
    if ($purpose !== 'download') {
        $shareFilePath = '/var/www/moksy.com/public_html/includes/common/share.php';
        if (file_exists($shareFilePath)) {
            include $shareFilePath;
        } else {
            throw new Exception('File does not exist: '.$shareFilePath);
        }
    }

    $previewScriptsPath = $previewBaseDir.'preview-scripts.php';
    if (file_exists($previewScriptsPath)) {
        include $previewScriptsPath;
    } else {
        throw new Exception('File does not exist: '.$previewScriptsPath);
    }
} catch (Exception $e) {
    // Log and handle exception as needed
    error_log($e->getMessage());
}
?>

</body>
</html>