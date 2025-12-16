

🎓 Campus Connect

Campus Connect is a web-based academic project designed to facilitate communication and content management within a university environment.
The project follows the MVC (Model–View–Controller) architecture for better organization and scalability.


---

📁 Project Structure

C:.
│   .gitignore
│   auth_config.php
│   composer.json
│   composer.lock
│   config.php
│
├───BasmaCRUD
│   │   index.php
│   │   README.md
│   │   README.pdf
│   │   test_validation.html
│   │
│   ├───config
│   │       config.php
│   │
│   ├───Control
│   │       adminAuth.php
│   │       evenementController.php
│   │       reservationController.php
│   │       statsController.php
│   │
│   ├───database
│   │       basmacrud.sql
│   │       evenements.sql
│   │       evenement_only_no_fk.sql
│   │       reservations.sql
│   │
│   ├───Model
│   │       db.php
│   │       Evenement.php
│   │       Reservation.php
│   │       Stats.php
│   │
│   └───View
│       │   add.php
│       │   delete.php
│       │   edit.php
│       │   list.php
│       │
│       ├───assets
│       │       controle_saisie.js
│       │       frontoffice.css
│       │       style.css
│       │       validation.js
│       │
│       ├───backoffice
│       │   │   add.php
│       │   │   edit.php
│       │   │   list.php
│       │   │   login.php
│       │   │   stats.php
│       │   │
│       │   └───reservations
│       │           add.php
│       │           edit.php
│       │           list.php
│       │
│       ├───frontoffice
│       │   │   index.php
│       │   │
│       │   └───reservations
│       │           edit.php
│       │           index.php
│       │
│       └───html
│               add.html
│               delete.html
│               edit.html
│               list.html
│
├───controllers
│       api_users.php
│       UserController.php
│
├───gestionquizz
│   │   .htaccess
│   │   certificate.php
│   │   index.php
│   │   play.php
│   │   quiz_result.php
│   │
│   ├───admin
│   │       add_question.php
│   │       add_quiz.php
│   │       delete_question.php
│   │       delete_quiz.php
│   │       edit_question.php
│   │       edit_quiz.php
│   │       index.php
│   │       list_questions.php
│   │       list_quizzes.php
│   │
│   ├───assets
│   │   ├───css
│   │   │       style.css
│   │   │
│   │   ├───js
│   │   │       validation.js
│   │   │
│   │   └───sounds
│   │           correct.mp3
│   │           incorrect.mp3
│   │
│   ├───config
│   │       database.php
│   │
│   ├───controllers
│   │       AdminQuestionController.php
│   │       AdminQuizController.php
│   │       QuestionController.php
│   │       QuizResultController.php
│   │
│   ├───libraries
│   │   └───tcpdf
│   │       │   CHANGELOG.TXT
│   │       │   composer.json
│   │       │   LICENSE.TXT
│   │       │   README.md
│   │       │   tcpdf.php
│   │       │   tcpdf_autoconfig.php
│   │       │   tcpdf_barcodes_1d.php
│   │       │   tcpdf_barcodes_2d.php
│   │       │   VERSION
│   │       │
│   │       ├───config
│   │       │       tcpdf_config.php
│   │       │       
│   │       ├───fonts
│   │       │   │   aealarabiya.ctg.z
│   │       │   │   aealarabiya.php
│   │       │   │   aealarabiya.z
│   │       │   │   aefurat.ctg.z
│   │       │   │   aefurat.php
│   │       │   │   aefurat.z
│   │       │   │   cid0cs.php
│   │       │   │   cid0ct.php
│   │       │   │   cid0jp.php
│   │       │   │   cid0kr.php
│   │       │   │   courier.php
│   │       │   │   courierb.php
│   │       │   │   courierbi.php
│   │       │   │   courieri.php
│   │       │   │   dejavusans.ctg.z
│   │       │   │   dejavusans.php
│   │       │   │   dejavusans.z
│   │       │   │   dejavusansb.ctg.z
│   │       │   │   dejavusansb.php
│   │       │   │   dejavusansb.z
│   │       │   │   dejavusansbi.ctg.z
│   │       │   │   dejavusansbi.php
│   │       │   │   dejavusansbi.z
│   │       │   │   dejavusanscondensed.ctg.z
│   │       │   │   dejavusanscondensed.php
│   │       │   │   dejavusanscondensed.z
│   │       │   │   dejavusanscondensedb.ctg.z
│   │       │   │   dejavusanscondensedb.php
│   │       │   │   dejavusanscondensedb.z
│   │       │   │   dejavusanscondensedbi.ctg.z
│   │       │   │   dejavusanscondensedbi.php
│   │       │   │   dejavusanscondensedbi.z
│   │       │   │   dejavusanscondensedi.ctg.z
│   │       │   │   dejavusanscondensedi.php
│   │       │   │   dejavusanscondensedi.z
│   │       │   │   dejavusansextralight.ctg.z
│   │       │   │   dejavusansextralight.php
│   │       │   │   dejavusansextralight.z
│   │       │   │   dejavusansi.ctg.z
│   │       │   │   dejavusansi.php
│   │       │   │   dejavusansi.z
│   │       │   │   dejavusansmono.ctg.z
│   │       │   │   dejavusansmono.php
│   │       │   │   dejavusansmono.z
│   │       │   │   dejavusansmonob.ctg.z
│   │       │   │   dejavusansmonob.php
│   │       │   │   dejavusansmonob.z
│   │       │   │   dejavusansmonobi.ctg.z
│   │       │   │   dejavusansmonobi.php
│   │       │   │   dejavusansmonobi.z
│   │       │   │   dejavusansmonoi.ctg.z
│   │       │   │   dejavusansmonoi.php
│   │       │   │   dejavusansmonoi.z
│   │       │   │   dejavuserif.ctg.z
│   │       │   │   dejavuserif.php
│   │       │   │   dejavuserif.z
│   │       │   │   dejavuserifb.ctg.z
│   │       │   │   dejavuserifb.php
│   │       │   │   dejavuserifb.z
│   │       │   │   dejavuserifbi.ctg.z
│   │       │   │   dejavuserifbi.php
│   │       │   │   dejavuserifbi.z
│   │       │   │   dejavuserifcondensed.ctg.z
│   │       │   │   dejavuserifcondensed.php
│   │       │   │   dejavuserifcondensed.z
│   │       │   │   dejavuserifcondensedb.ctg.z
│   │       │   │   dejavuserifcondensedb.php
│   │       │   │   dejavuserifcondensedb.z
│   │       │   │   dejavuserifcondensedbi.ctg.z
│   │       │   │   dejavuserifcondensedbi.php
│   │       │   │   dejavuserifcondensedbi.z
│   │       │   │   dejavuserifcondensedi.ctg.z
│   │       │   │   dejavuserifcondensedi.php
│   │       │   │   dejavuserifcondensedi.z
│   │       │   │   dejavuserifi.ctg.z
│   │       │   │   dejavuserifi.php
│   │       │   │   dejavuserifi.z
│   │       │   │   freemono.ctg.z
│   │       │   │   freemono.php
│   │       │   │   freemono.z
│   │       │   │   freemonob.ctg.z
│   │       │   │   freemonob.php
│   │       │   │   freemonob.z
│   │       │   │   freemonobi.ctg.z
│   │       │   │   freemonobi.php
│   │       │   │   freemonobi.z
│   │       │   │   freemonoi.ctg.z
│   │       │   │   freemonoi.php
│   │       │   │   freemonoi.z
│   │       │   │   freesans.ctg.z
│   │       │   │   freesans.php
│   │       │   │   freesans.z
│   │       │   │   freesansb.ctg.z
│   │       │   │   freesansb.php
│   │       │   │   freesansb.z
│   │       │   │   freesansbi.ctg.z
│   │       │   │   freesansbi.php
│   │       │   │   freesansbi.z
│   │       │   │   freesansi.ctg.z
│   │       │   │   freesansi.php
│   │       │   │   freesansi.z
│   │       │   │   freeserif.ctg.z
│   │       │   │   freeserif.php
│   │       │   │   freeserif.z
│   │       │   │   freeserifb.ctg.z
│   │       │   │   freeserifb.php
│   │       │   │   freeserifb.z
│   │       │   │   freeserifbi.ctg.z
│   │       │   │   freeserifbi.php
│   │       │   │   freeserifbi.z
│   │       │   │   freeserifi.ctg.z
│   │       │   │   freeserifi.php
│   │       │   │   freeserifi.z
│   │       │   │   helvetica.php
│   │       │   │   helveticab.php
│   │       │   │   helveticabi.php
│   │       │   │   helveticai.php
│   │       │   │   hysmyeongjostdmedium.php
│   │       │   │   kozgopromedium.php
│   │       │   │   kozminproregular.php
│   │       │   │   msungstdlight.php
│   │       │   │   pdfacourier.php
│   │       │   │   pdfacourier.z
│   │       │   │   pdfacourierb.php
│   │       │   │   pdfacourierb.z
│   │       │   │   pdfacourierbi.php
│   │       │   │   pdfacourierbi.z
│   │       │   │   pdfacourieri.php
│   │       │   │   pdfacourieri.z
│   │       │   │   pdfahelvetica.php
│   │       │   │   pdfahelvetica.z
│   │       │   │   pdfahelveticab.php
│   │       │   │   pdfahelveticab.z
│   │       │   │   pdfahelveticabi.php
│   │       │   │   pdfahelveticabi.z
│   │       │   │   pdfahelveticai.php
│   │       │   │   pdfahelveticai.z
│   │       │   │   pdfasymbol.php
│   │       │   │   pdfasymbol.z
│   │       │   │   pdfatimes.php
│   │       │   │   pdfatimes.z
│   │       │   │   pdfatimesb.php
│   │       │   │   pdfatimesb.z
│   │       │   │   pdfatimesbi.php
│   │       │   │   pdfatimesbi.z
│   │       │   │   pdfatimesi.php
│   │       │   │   pdfatimesi.z
│   │       │   │   pdfazapfdingbats.php
│   │       │   │   pdfazapfdingbats.z
│   │       │   │   stsongstdlight.php
│   │       │   │   symbol.php
│   │       │   │   times.php
│   │       │   │   timesb.php
│   │       │   │   timesbi.php
│   │       │   │   timesi.php
│   │       │   │   uni2cid_ac15.php
│   │       │   │   uni2cid_ag15.php
│   │       │   │   uni2cid_aj16.php
│   │       │   │   uni2cid_ak12.php
│   │       │   │   zapfdingbats.php
│   │       │   │
│   │       │   ├───ae_fonts_2.0
│   │       │   │       ChangeLog
│   │       │   │       COPYING
│   │       │   │       README
│   │       │   │
│   │       │   ├───dejavu-fonts-ttf-2.33
│   │       │   │       AUTHORS
│   │       │   │       BUGS
│   │       │   │       langcover.txt
│   │       │   │       LICENSE
│   │       │   │       NEWS
│   │       │   │       README
│   │       │   │       unicover.txt
│   │       │   │
│   │       │   ├───dejavu-fonts-ttf-2.34
│   │       │   │       AUTHORS
│   │       │   │       BUGS
│   │       │   │       langcover.txt
│   │       │   │       LICENSE
│   │       │   │       NEWS
│   │       │   │       README
│   │       │   │       unicover.txt
│   │       │   │
│   │       │   ├───freefont-20100919
│   │       │   │       AUTHORS
│   │       │   │       ChangeLog
│   │       │   │       COPYING
│   │       │   │       CREDITS
│   │       │   │       INSTALL
│   │       │   │       README
│   │       │   │
│   │       │   └───freefont-20120503
│   │       │           AUTHORS
│   │       │           ChangeLog
│   │       │           COPYING
│   │       │           CREDITS
│   │       │           INSTALL
│   │       │           README
│   │       │           TROUBLESHOOTING
│   │       │           USAGE
│   │       │
│   │       ├───include
│   │       │   │   sRGB.icc
│   │       │   │   tcpdf_colors.php
│   │       │   │   tcpdf_filters.php
│   │       │   │   tcpdf_fonts.php
│   │       │   │   tcpdf_font_data.php
│   │       │   │   tcpdf_images.php
│   │       │   │   tcpdf_static.php
│   │       │   │
│   │       │   └───barcodes
│   │       │           datamatrix.php
│   │       │           pdf417.php
│   │       │           qrcode.php
│   │       │
│   │       └───tools
│   │               .htaccess
│   │               convert_fonts_examples.txt
│   │               tcpdf_addfont.php
│   │
│   ├───models
│   │       Question.php
│   │       Quiz.php
│   │
│   └───views
│       ├───admin
│       │       form_question.php
│       │       form_quiz.php
│       │       list_questions.php
│       │       list_quizzes.php
│       │
│       ├───front
│       │       quiz_list.php
│       │       quiz_play.php
│       │       quiz_result.php
│       │
│       └───layout
│               footer.php
│               header.php
│
├───gestion_messagerie2
│   │   header.php
│   │   index.php
│   │
│   ├───assets
│   │   ├───css
│   │   │       edit-modal.css
│   │   │       style.css
│   │   │
│   │   ├───images
│   │   │       logo.jpg
│   │   │
│   │   └───js
│   │           edit-functions.js
│   │           messages.js
│   │           script.js
│   │           validation.js
│   │
│   ├───controller
│   │       messagesC.php
│   │       postController.php
│   │
│   ├───model
│   │       db.php
│   │       group.php
│   │       message.php
│   │       post.php
│   │       user.php
│   │
│   └───view
│       ├───Back-office
│       │       admin.php
│       │       debug.php
│       │       debug_messages.php
│       │       updatecomment_admin.php
│       │       update_post_admin.php
│       │
│       └───Front-office
│               addcomment.php
│               addgroupmessage.php
│               addpost.php
│               deletecomment.php
│               deletegroupmessage.php
│               deletemessage.php
│               deletepost.php
│               group.php
│               group_messages.php
│               like.php
│               messages.php
│               updatecomment.php
│               updategroupmessage.php
│               updatemessage.php
│               update_post.php
│
├───model
│       User.php
│
├───temp
│       attempts.json
│       lockouts.json
│
├───vendor
│   │   autoload.php
│   │
│   ├───composer
│   │       autoload_classmap.php
│   │       autoload_namespaces.php
│   │       autoload_psr4.php
│   │       autoload_real.php
│   │       autoload_static.php
│   │       ClassLoader.php
│   │       installed.json
│   │       installed.php
│   │       InstalledVersions.php
│   │       LICENSE
│   │       platform_check.php
│   │
│   └───phpmailer
│       └───phpmailer
│           │   COMMITMENT
│           │   composer.json
│           │   get_oauth_token.php
│           │   LICENSE
│           │   README.md
│           │   SECURITY.md
│           │   SMTPUTF8.md
│           │   VERSION
│           │
│           ├───language
│           │       phpmailer.lang-af.php
│           │       phpmailer.lang-ar.php
│           │       phpmailer.lang-as.php
│           │       phpmailer.lang-az.php
│           │       phpmailer.lang-ba.php
│           │       phpmailer.lang-be.php
│           │       phpmailer.lang-bg.php
│           │       phpmailer.lang-bn.php
│           │       phpmailer.lang-ca.php
│           │       phpmailer.lang-cs.php
│           │       phpmailer.lang-da.php
│           │       phpmailer.lang-de.php
│           │       phpmailer.lang-el.php
│           │       phpmailer.lang-eo.php
│           │       phpmailer.lang-es.php
│           │       phpmailer.lang-et.php
│           │       phpmailer.lang-fa.php
│           │       phpmailer.lang-fi.php
│           │       phpmailer.lang-fo.php
│           │       phpmailer.lang-fr.php
│           │       phpmailer.lang-gl.php
│           │       phpmailer.lang-he.php
│           │       phpmailer.lang-hi.php
│           │       phpmailer.lang-hr.php
│           │       phpmailer.lang-hu.php
│           │       phpmailer.lang-hy.php
│           │       phpmailer.lang-id.php
│           │       phpmailer.lang-it.php
│           │       phpmailer.lang-ja.php
│           │       phpmailer.lang-ka.php
│           │       phpmailer.lang-ko.php
│           │       phpmailer.lang-ku.php
│           │       phpmailer.lang-lt.php
│           │       phpmailer.lang-lv.php
│           │       phpmailer.lang-mg.php
│           │       phpmailer.lang-mn.php
│           │       phpmailer.lang-ms.php
│           │       phpmailer.lang-nb.php
│           │       phpmailer.lang-nl.php
│           │       phpmailer.lang-pl.php
│           │       phpmailer.lang-pt.php
│           │       phpmailer.lang-pt_br.php
│           │       phpmailer.lang-ro.php
│           │       phpmailer.lang-ru.php
│           │       phpmailer.lang-si.php
│           │       phpmailer.lang-sk.php
│           │       phpmailer.lang-sl.php
│           │       phpmailer.lang-sr.php
│           │       phpmailer.lang-sr_latn.php
│           │       phpmailer.lang-sv.php
│           │       phpmailer.lang-tl.php
│           │       phpmailer.lang-tr.php
│           │       phpmailer.lang-uk.php
│           │       phpmailer.lang-ur.php
│           │       phpmailer.lang-vi.php
│           │       phpmailer.lang-zh.php
│           │       phpmailer.lang-zh_cn.php
│           │
│           └───src
│                   DSNConfigurator.php
│                   Exception.php
│                   OAuth.php
│                   OAuthTokenProvider.php
│                   PHPMailer.php
│                   POP3.php
│                   SMTP.php
│
├───view
│   ├───BackOffice
│   │   │   adduser.php
│   │   │   admin_login.php
│   │   │   deleteuser.php
│   │   │   edit_user.php
│   │   │   index.php
│   │   │   index1.php
│   │   │   login.php
│   │   │   logout.php
│   │   │   showuser.php
│   │   │   updateuser.php
│   │   │   userlist.php
│   │   │
│   │   ├───css
│   │   │       profile.css
│   │   │       style-gestion.css
│   │   │       style_admin_login.css
│   │   │       style_login.css
│   │   │       style_signup.css
│   │   │
│   │   ├───images
│   │   │       logo.png
│   │   │
│   │   ├───js
│   │   │       admin_login.js
│   │   │       gestion.js
│   │   │       profile.js
│   │   │       signup.js
│   │   │
│   │   └───uploads
│   │       ├───banners
│   │       │       30.jpg
│   │       │       31.jpg
│   │       │
│   │       └───profiles
│   │               22.jpg
│   │               30.png
│   │               31.jpg
│   │
│   └───FrontOffice
│       │   check-files.php
│       │   db_test.php
│       │   forgot-password.php
│       │   homepage.php
│       │   index.php
│       │   login.php
│       │   logout.php
│       │   process-forgot.php
│       │   profile.php
│       │   reset-password.php
│       │   signup.php
│       │   test-search.html
│       │
│       ├───css
│       │       profile.css
│       │       style-gestion.css
│       │       style_admin_login.css
│       │       style_login.css
│       │       style_signup.css
│       │
│       ├───images
│       │       logo.png
│       │
│       ├───js
│       │       admin_login.js
│       │       gestion.js
│       │       profile.js
│       │       signup.js
│       │
│       └───uploads
│           ├───banners
│           │       30.jpg
│           │       31.jpg
│           │
│           ├───lottie
│           │       Book loading.json
│           │
│           └───profiles
│                   22.jpg
│                   30.png
│                   31.jpg
│
└───VV13
    │   index.php
    │   README.md
    │
    ├───config
    │       config.php
    │
    ├───Control
    │       matiereController.php
    │       ressourceController.php
    │
    ├───Model
    │       db.php
    │       Matiere.php
    │       Ressource.php
    │
    └───View
        │   add.php
        │   delete.php
        │   edit.php
        │   list.php
        │
        ├───assets
        │       frontoffice.css
        │       style.css
        │       validation.js
        │
        ├───backoffice
        │       add.php
        │       admin_dashboard.php
        │       edit.php
        │       favoris_list.php
        │       list.php
        │       logs.php
        │       partages_list.php
        │       ressource_add.php
        │       ressource_edit.php
        │       ressource_list.php
        │       sidebar.php
        │       telechargements_list.php
        │       test.php
        │
        ├───frontoffice
        │       activites_index.php
        │       favoris_index.php
        │       index.php
        │       partages_index.php
        │       ressource_index.php
        │       telechargements_index.php
        │
        └───html
                add.html
                delete.html
                edit.html
                list.html
---

⚙️ Technologies

PHP

HTML5 / CSS3

JavaScript

MVC Architecture




🎯 Objective

This project was developed as a school/academic project to apply:

MVC architecture

Clean project structure

Web development fundamentals



---

👤 Author

Campus Connect – Academic Web Project


