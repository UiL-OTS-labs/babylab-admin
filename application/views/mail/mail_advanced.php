<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style type="text/css">
        /* Basics */
        body {
            Margin: 0;
            padding: 0;
            min-width: 100%;
            background-color: #ffffff;
        }
        table {
            border-spacing: 0;
            font-family: sans-serif;
            color: #333333;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
            display: block;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .webkit {
            max-width: 600px;
        }
        .outer {
            Margin: 0 auto;
            width: 95%;
            max-width: 600px;
        }
        .full-width-image img {
            width: 100%;
            height: auto;
            max-width: 238px;
        }

        /** Header **/
        p.babylab-title{
            color: #111111;
            font-size: 2em;
            margin-top: .5em;
        }

        div.header-seperator{
            background-color: #094D8E;
            border-radius: 10px 10px 0px 0px;
            text-align: center;
            padding: 3px;
        }

        p.babylab-subtitle{
            color: #FFFFFF;
            font-size: .9em;
        }

        /* Content */
        .inner {
            padding: 10px;
        }
        p {
            Margin: 0;
        }
        a {
            color: #ee6a56;
            text-decoration: underline;
        }
        .h1 {
            font-size: 21px;
            font-weight: bold;
            Margin-bottom: 18px;
        }
        .h2 {
            font-size: 18px;
            font-weight: bold;
            Margin-bottom: 12px;
        }
         
        /* One column layout */
        .one-column .contents {
            text-align: left;
        }
        .one-column p {
            font-size: 14px;
            Margin-bottom: 10px;
        }
        /*Two column layout*/
        .two-column {
            text-align: center;
            font-size: 0;
        }

        .two-column .column {
            width: 100%;
            max-width: 280px;
            display: inline-block;
            vertical-align: top;
        }

        .contents {
            width: 100%;
            text-align: left;
        }

        .two-column .contents {
            font-size: 14px;
            text-align: left;
        }
        .two-column img {
            width: 100%;
            height: auto;
        }
        .two-column .text {
            padding-top: 10px;
        }

        .mail-content{
            background-color: #E6EBF0;
        }

        .mail-content a{
            color: #ffffff;
            background-color: #064480;
            text-decoration: none;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 5px;
        }

        .mail-content .body-link-color{
            color: #fff;
        }

        /* footer */
        .footer .two-column{
            background-color: #094D8E;
            border-radius: 0px 0px 10px 10px;
        }

        .disclaimer{
            font-size: .8em;
            color: #fff;
        }

        .footer .footer-menu td{
            display: block;
            Margin-bottom: 5px;
            width: 100%;
        }

        .footer .footer-menu a{
            font-size: .8em;
            text-align: center;
            display: block;
            color: #ffffff;
            background-color: #064480;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 0px;
            border-radius: 5px;
            width: 100%;
        }

        .footer .footer-menu a:hover, .footer .footer-menu a:active{
            background-color: #094D8F;
        }

         
        /* Windows Phone Viewport Fix */
        @-ms-viewport {
            width: device-width;
        }
    </style>
    <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        table {border-collapse: collapse;}
    </style>
    <![endif]-->
</head>
<body>
    <center class="wrapper">
        <div class="webkit">
            <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="center">
                <tr>
                <td>
            <![endif]-->
            <table class="outer" align="center"> <!-- Outer table on everything except outlook -->

                <!-- header -->
                <tr>
                    <td class="two-column">
                        <!--[if (gte mso 9)|(IE)]>
                        <table width="100%">
                        <tr>
                        <td width="50%" valign="top">
                        <![endif]-->
                      
                         <div class="column">
                            <table width="100%">
                                <tr>
                                    <td class="inner">
                                       <table class="contents">
                                            <tr>
                                                <td>
                                                    <p class="babylab-title">Babylab Utrecht</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td><td width="50%" valign="top">
                        <![endif]-->
                         <div class="column">
                            <table width="100%">
                                <tr>
                                    <td class="inner">
                                        <table class="contents">
                                            <tr>
                                                <td>
                                                    <div class="full-width-image">
                                                        <img src="http://wp.hum.uu.nl/wp-content/themes/humanities/images/logo-uu-header.png" alt="">
                                                    </div>
                                                </td>
                                            </tr> 
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="header-seperator">
                            <p class="babylab-subtitle"><?=lang('babylab_subtitle');?></p>
                        </div>
                    </td>
                </tr>



                <!-- Content -->
                <tr class="mail-content-row">
                    <td class="one-column mail-content">
                        <table width="100%">
                            <tr>
                                <td class="inner contents">
                                    <p class="h1"><?=$hello;?></p>
                                    <p>Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent laoreet malesuada cursus. Maecenas scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat.</p>
                                    <p>You can check this link <a href="#"><span class="body-link-color">here</span></a></p>
                                    <p>If this link doesn't work, copy the following text to your browser:<br/>jkalsdasdfasdfasdf</p>
                                    <p><?=$mail_ending;?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>


                <!-- Footer -->

                <tr class="footer">
                    <td class="two-column">
                        <!--[if (gte mso 9)|(IE)]>
                        <table width="100%">
                        <tr>
                        <td width="50%" valign="top">
                        <![endif]-->
                        <div class="column">
                            <table width="100%">
                                <tr>
                                    <td class="inner">
                                        <table class="contents disclaimer-table">
                                            <tr>
                                                <td>
                                                    <div class="disclaimer">
                                                        <?=lang('mail_disclaimer');?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td><td width="50%" valign="top">
                        <![endif]-->
                        <div class="column">
                            <table width="100%">
                                <tr>
                                    <td class="inner">
                                        <table class="contents footer-menu">
                                            <?php if ($toClient) : ?>
                                            <tr>
                                                <td><a href="#"><span class="footer-menu-link-color">Afmelden</span></a></td>
                                            </tr>
                                        <?php endif; if ($toAdmin) : ?>
                                            <tr>
                                                <td><a href="#"><span class="footer-menu-link-color">Administratieve Interface</span></a></td>
                                            </tr>
                                        <?php endif; if ($toClient) : ?>
                                            <tr>
                                                <td><a href="#"><span class="footer-menu-link-color">Babylab website</span></a></td>
                                            </tr>
                                        <?php endif; if ($toClient) : ?>
                                            <tr>
                                                <td><a href="#"><span class="footer-menu-link-color">Inschrijving wijzigen</span></a></td>
                                            </tr>
                                        <?php endif; ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
            <![endif]-->
        </div>
    </center>
</body>