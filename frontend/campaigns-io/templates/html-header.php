<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $document_data['title']; ?></title>
    <?php enqueue_html_stylesheets( $document_data['styles'] ); ?>
    <?php enqueue_html_scripts( $document_data['top_scripts'] ); ?>
</head>

<body>