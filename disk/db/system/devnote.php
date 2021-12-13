<?php //tlex/db/disk/db/system/devnote.php
$r=['_'=>['uid','tit','txt'],1=>['1','170110 - Badger','- ajout du badger, permet de switcher d\'un comte à l\'autre
- ajout de devnotes, permet d\'historiser les étapes de la cristallisation logicielle'],2=>['1','170111 - Vue','- ajout de menus de gestion des couches de langages
- correction de l\'ordre des tâches de Vue pour que le constructeur de template ne génère pas de résultat lorsque les variables sont vides
- amélioration substantielle de'],3=>['1','170116 - Création d\'une App','Etapes de la création d\'une App :
- décliner une App depuis le fichier model.php : chercher-remplacer \'model\' par le nom de l\'App, et le renommer le fichier comme tel.
- dans /admin/icons : définir l\'icône pour cette App
- enfin'],4=>['1','170114 - gestion des Apps','les apps de télex sont listées dans le Desktop, dans le dossier app/telex'],5=>['1','170120 - slide','améliorations correctives et ergonomiques de Slide'],6=>['1','170121 - player philum','Ajout de l\'interpréteur json en provenance des Cms Philum.
Les articles des sites Philum s\'ouvrent en entier dans le player web, 
que ce soit depuis un connecteur :web (dans une popup) ou depuis le connecteur :philum'],7=>['13','170123 - lecture distante d\'articles','Ajout du bouton "lire" qui apparaît dans la prévisualisation d\'article, quand le contenu est disponible via une API'],8=>['1','170124 - phylo, structure de données','Ajout du nouveau composant système Phylo (phylogénie).
A tester sur /app/unit

Permet de construire un rendu compliqué où les informations d\'un tableau (simple) sont à dispatcher sur différentes balises, librement'],9=>['1','170125 - noconn(), profile/utils','- ajout du menu profile/utils où on dispose d\'un éditeur de prise de notes en localstorage
- ajout de Conn::noconn(), renvoie le contenu utilisable des connecteurs (format .txt)'],10=>['1','170126 - tit() est appelé lors du partage','- réforme de href() où les 2 derniers params sont intervertis (du plus courant au moins courant)
- la méthode tit() des apps est appelée lors du partage du message'],11=>['1','170126 - Twitter Api','Ajout du support de l\'Api Twitter
- ajout de la table admin_twitter
- éditable depuis le profile
- utilisable depuis le partage de message
- app/twitter permet de lire ses timelines et de publier des messages, avec son compte'],12=>['1','170126 - model','Amélioration substantielle de app/model :
- automatisation des colonnes (il suffit de les signaler une fois pour qu\'ils se répercutent)
- usage des templates et de Phylo'],13=>['1','170127 - stats','ajout de /stats, enregistre les pages vues et par qui'],14=>['1','170206 - yandex','ajout de l\'app yandex, api de traduction'],15=>['1','170210 - pray','Remise à niveau de l\'app Pray'],16=>['1','170214 - meet','L\'App Meet permet de fixer des rendez-vous de groupes'],17=>['1','170216 - notifs mail','Ajout des notifications par mail, désactivables dans le profile'],18=>['1','170217 - pray (réforme)','Rénovation de Pray : suppression du principe de sessions, ajout d\'aides'],19=>['1','170220 - spanish language','Ajout du support pour la langue espagnole'],20=>['1','170221 - pad','Ajout de l\'app Pad, éditeur en localstorage comme Txt mais en version html'],21=>['1','170222 - smoke','L\'App Smoke permet d\'afficher des messages d\'alerte, à la mode "Fumer tue"'],22=>['1','170223 - update','Framework :
/app/update will find new versions of files and download them'],23=>['1','170224 - harmonisation','Maintenance applicative :
- Harmonisation des anciennes Apps avec le modèle courant'],24=>['1','170226 - maintenance','Maintenance applicative
- ajout des pubs des comptes du même utilisateur
- affichage de la bonne couleur de fond lors de l\'utilisation du Badger'],25=>['1','170227 - chat associé','un bouton "chat privé" permet de lancer une discussion privée avec l\'auteur d\'un message'],26=>['1','170228 - upload img','Ajout d\'un upload d\'images ajax réplicable dans l\'édition du profile'],27=>['1','170301 - account closed','Le compte peut être désactivé, puis réactivé ultérieurement ;
il n\'apparaît plus dans le badger, ni au public.'],28=>['1','170302 - Menu et Desk','- le composant Menu devient (enfin) capable de créer à la volée des menus de niveau zéro en signalant seulement son nom dans la topologie, sans artifice /
- le composant Desk utilise en priorité les icônes de la ba'],29=>['1','170302 - helps','L\'organisation des aides fait que : 
- help($app) renvoie le nom commun de l\'App
- help($app.\'_app\') renvoie son descriptif.
Le premier est utilisé dans le titrage des Apps, et le second dans son l\'aide contextuelle.

Référencement de'],30=>['1','170303 - upgrade','Ajout de l\'App upgrade : 
dispositif de lancement automatique des mises à jour, des fichiers et des bases.
Fonctionne à la première connexion en tant qu\'admin.
Compare les numéros de version distant et local, puis lance update('],31=>['1','170304 - langs','admin_lang : le panneau d\'édition d\'un terme permet de passer d\'une langue à l\'autre, et commet une traduction si elle n\'existe pas déjà'],32=>['1','170308 - Vue reform','Réforme de la syntaxe des variables dans Vue, utilisant des parenthèses (var)'],33=>['1','170308 - Card','Ajout de Card, permet d\'éditer des cartes de visites'],34=>['1','170312 - maintenance','various working-well bugs
recognize user who have changed ip
better badger'],35=>['1','170317 - labels','Révision des Labels, plus génériques, moins nombreux, plus utiles, et multilingues'],36=>['1','170328 - art','l\'app modernisation de l\'app \'article\', renommée \'art\' : édition sur place, simplification

Art permet de créer des articles, dont l\'édition peut être rendue publique.'],37=>['1','170330 - editeur mixte','- Art se dote d\'un éditeur mixte wysiwyg + connecteurs, quand ceux-ci produisent un rendu qui, à l\'enregistrement, ferait perdre le connecteur d\'origine.
- On peut redimensionner les images.
- les id sont contextualisés (éditer'],38=>['1','170404 - Art','- L\'éditeur d\'article se dote d\'un enregistrement continu durant l\'édition et d\'une possibilité de restaurer la dernière version enregistrée.
- Le titre est éditable en cliquant dessus, le contenu en allumant l\'&e'],39=>['1','170406 - Tabler','amélioration substantielle de Tabler
- utilise une bdd et son admin
- utilise les connecteurs'],40=>['1','170407 - Ballot','Rénovation de l\'app Ballot (scrutin majoritaire)
- remise à niveau avec les nouvelles spécifications
- on peut déterminer la date de fin du scrutin'],41=>['1','170408 - update Fractal','- l\'admin est gérée par le framework (et soulage d\'autant l\'app Telex)'],42=>['1','170411 - model','nouveau model d\'app, utilisant un menu admin qui s\'affiche dans la barre de menus, de la page, de la popup, ou de l\'éditeur de tlex (centralisation des fonctions classiques en un procédé générique)'],43=>['1','170412 - Login','Réfection du login'],44=>['11','170413 - Slide','Réfection de Slide'],45=>['1','170414 - Appx','Introduction de appx, une classe abstraite des Apps, utilisée par model, smoke, card, devnote, tickets'],46=>['37','170416 - Vote','Rénovation du vote majoritaire, réécriture de l\'algo, mise aux nouvelles normes, nouvelle présentation'],47=>['19','170417 - Art','fix pb messages longs dont l\'enregistrement pouvait se chevaucher avec l\'enregistrement automatique'],48=>['1','170418 - tlex','refactoring de telex en tlex : nominations, pages, tables lang et help, htaccess, cnfg sont modifiés (update critique)'],49=>['19','170418 - collect datas','Ajout du support collect dans l\'appx (l\'app abstraite)
Pour obtenir les données collectées :
- la seconde table est définie dans $collect dans ::edit()
- la colonne bid est liée à id de la première table'],50=>['19','170418 - slide','remise à niveau de Slide, basé sur appx'],51=>['19','170418 - Decide','Ballot devient Vote et l\'ancien Vote devient Decide
Rénovation de Decide (adaptation à appx) et l\'app devient '],52=>['19','170419 - Tabler','Rénovation de Tabler, pour tourner sous Appx
les tableaux deviennent rééditables'],53=>['19','170419 - système des apps','Révision de App de façon à utiliser plus judicieusement les méthodes privées et les instances ; répercuté sur toutes les apps (update critique)'],54=>['19','170420 - map','Réfection de Map, fonctionne sous Appx'],55=>['19','170420 - chat','Réfection de Chat, qui tourne sous Appx'],56=>['19','170421 - fixs','- rectifs htaccess
- amélioration gestionnaire d\'appel des apps
- art reçoit la méthode txt()
- correctifs nouveau chat
- le desktop offre la visibilité pour les apps tlex aux niveaux d\'autorisation>=4, l\'accès public au'],57=>['19','170421 - Appx','Appx propose la gestion des documents publics.
Dans ce cas on accède à l\'exécution au lieu de l\'édition, si on n\'est pas l\'auteur.'],58=>['1','170422 - privacy','Appx se dote de paramètres de confidentalité.
Pour les activer l\'App doit avoir une colonne \'pub\' à la fin (pour les dev)
Ensuite l\'utilisateur peut régler les paramètres de son App de sorte que son accès soit pri'],59=>['19','170423 - Text','Introduction de Simple Text (toujours la mode apple old school revival)
c\'est juste une App de base avec les options activées, de confidentialité et de connecteurs.'],60=>['19','170423 - appx','Appx se dote d\'une admin qui permet, lors de la création d\'une App, de paramétrer ses nominations en différentes langues, sa description, son icône et sa présence dans les Apps de Tlex'],61=>['37','170424 - css','Réfection CSS, fixe pb affichage mobiles'],62=>['1','170425 - partage','- amélioration du partage, on peut avoir plusieurs apis twitter, fix pb encodage, fix pb connecteurs type noconn'],63=>['1','170425 - htaccess','on peut accéder à /app/model/2 directement directement via /model/2'],64=>['1','170425 - déroulé principal','Le déroulé ne présente plus le contenu des apps, tout étant standardisé, seul Art a le privilège d\'offrir une preview, les autres montrent des boutons précisant le nom de l\'App et le titre de l\'objet, qui s'],65=>['19','170426 - finalisation Appx','Finalisation de la grande mutation apportée par Appx
- fix css mobiles
- certaines apps profitent du dispositif de confidentalité
- correctifs sur Chat, Pray, Vote'],66=>['19','170427 - Appx choix multiples','Appx se dote du support des colonnes :
- answ : gère les choix multiples (utilisé par vote et poll)
- day : gère les dates
- nb : un nombre entre 1 et 10 sur une barre d\'évaluation
qui s\'ajoutent à txt, cl (close) et pub'],67=>['1','170429 - drop','introduction du procédé Drop
une option du procédé Menu
permet d\'avoir des menus ouvrables sur eux-mêmes, à la place des menus à tiroir (gain d\'espace sur les mobiles, et question de mode)'],68=>['1','170430 - Ajax','la classe Ajax est rendue obsolète
introduction de ajs() qui renvoie la partie js du bouton ajax, dans la lib'],69=>['19','170507 - dates','Ajout du support de dates dans la chaîne allant du métier Sql à Appx (meet, poll, pray et vote)'],70=>['19','170509 - appx security','Amélioration de la sécurité de Appx : empêche et signale les intrusions d\'édition, sauvegarde ou suppression non autorisées'],71=>['1','170510 - Loto','Ajout de l\'App Loto (en berne)'],72=>['1','170511 - Book','Ajout de l\'App Book : permet de créer un livre composé de chapitres. 
ça fait son effet, on est fiers de notre oeuvre :)'],73=>['1','170515 - imgtxt','Ajout de l\'App Imgtxt, permet de créer des image-texte.
- ajout de la propriété $open dans les apps, permet de décider si une app s\'ouvre directement dans tlex (au premier appel d\'une app dans un message, sinon ne s\'affiche qu\''],74=>['19','170515 - Appx com','Ajout du gestionnaire spécifique à la colonne \'com\' dans Appx : permet d\'assumer des paramètres multiples.
- utilisé dans Form, qui s\'y conforme, rendant obsolètes les anciennes occurrences.
- réfection du gestion'],75=>['1','170516 - imgtxt, sticker','Ajout des Apps 
- imgtxt : permet de créer des image contenant du texte
- sticker : permet de créer des affches, en partant d\'une image, et en y ajoutant (de façon sommaire) du texte, avec ses propriétés de taille, coule'],76=>['1','170517 - Vector','Ajout de l\'App Vector : permet de dessiner des formes vectorielles, en utilisant les connecteurs SVG (voir /app/_svg pour s\'entraîner)'],77=>['1','170517 - php','Ajout de l\'App php, très simple, permet de présenter (et partager) du code source'],78=>['1','170518 - pdf','Ajout du support PDF dans le traitement des messages. Un lien .pdf va engendrer un connecteur :pdf, qui renvoie un lien vers une pagup, où s\'ouvre une iframe. '],79=>['19','170518 - pswd','Dans le profile, ajout du support de modification de mot de passe'],80=>['1','170524 - voting','Ajout de l\'App "Vigilance citoyenne", permet d\'enregistrer les résultats d\'une élection'],81=>['19','170524 - bank','Premiers jets de l\'App Bank
- gestion d\'objets
- gestion de ressourses'],82=>['1','170621 - iframes','ajout du support d\'intégration d\'un message dans une iframe (menu Partager)'],83=>['19','170530 - Bank','Bank est opérationnel, et séparé de sa gestion d\'objets, sert à gérer ses comptes de jetons rouges, verts et bleus.'],84=>['19','170531 - role','Profile se dote d\'une colonne \'role\', qui permet de catégoriser un compte d\'après les champs :
- humain
- groupe
- association
- industrie
- institution'],85=>['19','170805 - to et tag','ajout des tables tlex_to et tlex_tag, qui gèrent les destinataires et les tags des messages
- détection lors de l\'insertion
- attaché au déclencheur de suppression'],86=>['1','171019 - dB','ajout du composant dB, système de base de données NoSql'],87=>['1','171019 - Mem','Ajout du composant Mem, système de base de données scalable (open to big data)'],88=>['1','171101 - Explorer','Explorer permet de naviguer et d\'éditer des dossiers et des bases de données, de type json ou dB (deciBels, sgdb no-sql, marque de Tlex).'],89=>['1','171102 - Artwork','Sert à compiler des articles de Art'],90=>['19','171103 - encodage','harmonisation des systèmes d\'encodage en utf8mb4'],91=>['1','171108 - Editable','Explorer se dote de plus d\'outils de gestion des données.
re-conception de l\'architecture des urls (autorisations, etc...)
Tabler propose d\'enregistrer une table (source de données) dans sont espace privé.
Editable est un composant qui rend le tableau '],92=>['1','171108 - roots','- la règle de barre d\'url change, l\'app est appelée à la /racine, et tlex utilise /id/ et /usr/. un /numérique renvoie un article dans son contexte tlex (blog).'],93=>['1','171108 - Api','- révision d\'un protocole de l\'Api, usage des url de type /app/p=1,o=2
- ajout du support automatique de rendu via l\'Api de toutes les apps, qui exploitent le moteur appx'],94=>['19','171109 - façade','retravail de la façade du logiciel
- l\'ensemble des boutons sont déposés dans un seul bloc de menu
- le logiciel est remodelé pour en faire une plateforme d\'Apps'],95=>['19','171110 - tabler db','jonction entre Tabler et dB :
- les tables peuvent être enregistrées en dB
- les bases de données dB peuvent être importées dans Tabler (pour y être publiées)
- ajout du connecteur [:db] qui reçoit le root en paramètre']];