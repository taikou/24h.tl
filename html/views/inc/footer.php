<? if(!preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){ ?>
<? /*
<script src="public/smartbanner/smart-app-banner.js"></script>
    <script type="text/javascript">
      new SmartBanner({
          daysHidden: 15,   // days to hide banner after close button is clicked (defaults to 15)
          daysReminder: 90, // days to hide banner after "VIEW" button is clicked (defaults to 90)
          appStoreLanguage: 'us', // language code for the App Store (defaults to user's browser language)
          title: '24h.timeline',
          author: 'Decoo,inc.',
          button: 'VIEW',
          store: {
              ios: 'On the App Store',
              android: 'In Google Play',
          },
          price: {
              ios: 'FREE',
              android: 'FREE',
          },
		  force: '<?=(preg_match('/iPhone/',$_SERVER['HTTP_USER_AGENT'])?'ios':'android')?>'
          // , theme: '' // put platform type ('ios', 'android', etc.) here to force single theme on all device
          // , icon: '' // full path to icon image if not using website icon image
          // , force: 'ios' // Uncomment for platform emulation
      });
</script>
*/ ?>


<? } ?>






<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118171300-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118171300-1');
</script>