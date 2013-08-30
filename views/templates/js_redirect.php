<head>
  <meta charset="utf-8">
  <title>Brugal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
</head>
<body>
  <form id="redirect" 
        action="<?php echo self::session('redirect');?>" 
        method="post" 
        style="display: none;"
        >
    <?php foreach (self::session('request') as $key => $value): ?>
      <input hidden name="<?php echo $key;?>" value="<?php echo $value;?>" />
    <?php endforeach;?>
    <input hidden name="stateless_session" value="stateless_session"/>
    <input hidden name="method" value="GET" />
    <input hidden type="submit" value="redirect" />
  </form>
</body>
<script type="text/javascript">
  function submit_now(){
    document.forms["redirect"].submit();
  }
  submit_now();
</script>
