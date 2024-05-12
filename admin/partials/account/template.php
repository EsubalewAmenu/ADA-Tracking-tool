<!DOCTYPE html>
<html lang="en" style="margin: 0; padding: 0;">
  <head style="margin: 0; padding: 0">
    <meta charset="UTF-8" style="margin: 0; padding: 0" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0"
      style="margin: 0; padding: 0"
    />
    <meta name="color-scheme" content="only">
    <title style="margin: 0; padding: 0">{{--subject--}}</title>
  </head>
  <body
   style=" background: linear-gradient(to bottom, #032E44, #01243E, #22223B);"
  >
  <div class="parent" style="
      font-family:roboto;
      margin: auto;
      padding: 0;
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      flex-direction: column;
      text-align: center;
      align-items: center;
      justify-content: center;
      color: #fff !important;
      max-width: 600px;
      margin: auto;
      padding:5px;
    ">
   
    <main class="main"
      style="
        margin: auto;
        padding: 0;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
      "
    >
     
      
      <div
        class="main_para"
        style="
          margin: 0;
          padding: 0;
          width: 500px;
          text-align: start;
          color: #FFFFFF !important;
          /* line-height: 22px; */
          font-family: roboto;
          text-overflow: ellipsis;
          overflow: hidden;
        "
      >
        <p
          class="para_1"
          style="
            margin: 0;
            padding: 0;
            font-family: roboto;
            font-size: 15px;
            line-height: 30px;
            margin-bottom: 16px;
          "
        >
        {{--body--}}
        <br><br>
        <p class="para_5" style="margin-bottom: 16px; padding-top:30px;">
          Thanks,<br/>
          Team <?php get_bloginfo('name') ?>.
        </p>
      </div>
      <div class="social-icons">
				<a href="https://twitter.com/esubalewa" target="_blank">
					<img src="https://similarpng.com/wp-content/uploadPngfree/thumbnail/2024/03/X-logo---Twitter-logo-icon-in-round-black-color-on-transparent-PNG.png"  alt="twitter" width="20px" style="padding-bottom:5px;margin:0 5px;">
				</a>
				<a href="https://www.linkedin.com/in/esubalew-amenu/" target="_blank">
					<img src="https://image.similarpng.com/very-thumbnail/2020/07/Linkedin-logo-on-transparent-Background-PNG-.png"  alt="linkedin" width="20px" style="padding-bottom:5px;margin:0 5px;">
				</a>
				<a href="https://t.me/esubalewa" target="_blank">
					<img src="https://image.similarpng.com/very-thumbnail/2020/07/Telegram-icon-on-transparent-background-PNG.png" alt="telegram" width="20px" style="padding-bottom:5px;margin:0 5px;" >
				</a>
			</div>

      <img
        src="{{--home_url--}}/wp-content/plugins/mp-mailing-list/email_templete/assets/img/Line91.png"
        alt=""
        class="line_bottom"
        style="margin: 15px auto; padding: 0"
      />
      <div class="main_footer" style="margin: 0; padding: 0">
        <ul style="margin: 0; padding: 0; list-style: none; text-align: center">
          <li
            style="
              margin: 0;
              padding: 4px 10px;
              list-style: none;
              display: inline-block;
              font-family: roboto;
              color: #FFFFFF;
              font-size: 15px;
              text-decoration: underline;
            "
          >
          <a style="color: #fff !important; " href="{{--home_url--}}/privacy-policy" target="_blank">
            Privacy
				  </a>
          </li>
          <li
            style="
              margin: 0;
              padding: 4px 10px;
              list-style: none;
              display: inline-block;
              font-family: roboto;
              color: #FFFFFF;
              font-size: 15px;
              text-decoration: underline;
              border-left: 2px #49FFB3 solid;
              border-right: 2px #49FFB3 solid;
            "
          >
          <a style="color: #fff !important; " href="{{--home_url--}}/terms" target="_blank">
          User Agreement
				  </a>
            
          </li>
          <li
            style="
              margin: 0;
              padding: 4px 10px;
              list-style: none;
              display: inline-block;
              font-family: roboto;
              color: #FFFFFF;
              font-size: 15px;
              text-decoration: underline;
            "
          >
          <a style="color: #fff !important; " href="{{--home_url--}}/edit-profile/?tab=settings" target="_blank">
            Unsubscribe
				  </a>
          </li>
        </ul>
        <div class="footer_logo" style="margin: 0; padding: 0">
          <h3
            style="
              margin: 0;
              padding: 0;
              font-family: Roboto;
              font-weight: bolder;
              margin-top: 16px;
              color: #FFFFFF;
            "
          >
            <?php get_bloginfo('name') ?>
          </h3>
        </div>
      </div>

    </main>
    </div>
  </body>
</html>