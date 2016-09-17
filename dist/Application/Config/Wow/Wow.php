<?php
    /**
    * Registers other expressions
    * apart from internal expressions
    */
    namespace Application;
    use Application\Core\Wow;


    //general echo
    Wow::register(
        Wow::rx("{{(.+?)}}", "i"),
        "<?php echo $1; ?>"
    );


    if(Wow::flavor()<=Wow::COMBINED) {
        //
        //    <echo>$username</echo>
        //
        Wow::register(
            Wow::rx("\<echo\>(.+?)\<\/echo\>", "i"),
            "<?php echo $1; ?>"
        );


        //
        //    <php>
        //
        //        echo 'ok';
        //
        //    </php>
        //
        Wow::register(
            Wow::rx("\<php\>(.+?)\<\/php\>", "is"),
            "<?php $1 ?>"
        );


        //
        //    <if>
        //        <condition>1==1</condition>
        //
        //        echo "1 is 1";
        //
        //    </if>
        //
        Wow::register(
            Wow::rx("\<if\>\s*\<condition\>(.+?)\<\/condition\>(.+?)\<\/if\>", "is"),
            "<?php if($1) { ?>$2<?php } ?>"
        );


        //
        //    Can only be used inside an if
        //    <elseif>
        //        <condition>1==1</condition>
        //
        //        echo "1 is 1";
        //
        Wow::register(
            Wow::rx("\<elseif\>\s*\<condition\>(.+?)\<\/condition\>(.+?)", "is"),
            "<?php } elseif($1) { ?>$2"
        );


        //
        //    Can only be used inside an if
        //    <else>
        //
        //        echo "1 is 1";
        //
        Wow::register(
            Wow::rx("\<else\>(.+?)", "is"),
            "<?php } else { ?>$1"
        );




        //
        //    <for>
        //        <loop>$i=0;...++$i</loop>
        //        <li>..</li>
        //    </for>
        //
        Wow::register(
            Wow::rx("\<for\>\s*\<loop\>(.+?)\<\/loop\>(.+?)\<\/for\>", "is"),
            "<?php for($1) { ?>$2<?php } ?>"
        );


        //
        //    <foreach>
        //        <loop>$i in $b</loop>
        //        <li>..</li>
        //    </foreach>
        //
        Wow::register(
            Wow::rx("\<foreach\>\s*\<loop\>(.+?)\<\/loop\>(.+?)\<\/foreach\>", "is"),
            "<?php foreach($1) { ?>$2<?php } ?>"
        );


        //
        //    <while>
        //        <condition>$i < 10</condition>
        //        <li>..</li>
        //    </while>
        //
        Wow::register(
            Wow::rx("\<while\>\s*\<condition\>(.+?)\<\/condition\>(.+?)\<\/while\>", "is"),
            "<?php while($1) { ?>$2<?php } ?>"
        );


        //
        //    <css href="/css/style.css"/>
        //
        Wow::register(
            Wow::rx("\<css\s+href=\"(.+?)\"\s*\/\>","i"),
            '<link rel="stylesheet" href="$1" type="text/css">'
        );
        //
        //    <css>/css/style.css</css>
        //
        Wow::register(
            Wow::rx("\<css\>(.+?)\<\/css\>","is"),
            '<link rel="stylesheet" href="$1" type="text/css">'
        );


        //
        //    . file notation can be used but is not necessary
        //    <css embed="css.style.css"/>
        //
        Wow::register(
            Wow::rx("\<css\s+embed=\"(.+?)\"\s*\/\>","i"),
            '<style type="text/css"><?php echo Core\FileHandler::public(\'$1\')->read(); ?></style>'
        );
        //
        //    . file notation can be used but is not necessary
        //    <css embed>css.style.css</css>
        //
        Wow::register(
            Wow::rx("\<css\s+embed\s*\>(.+?)\<\/css\>","is"),
            '<style type="text/css"><?php echo Core\FileHandler::public(Core\App::location().\'/\'.\'$1\')->read(); ?></style>'
        );


        //
        //    <css external-embed="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
        //
        Wow::register(
            Wow::rx("\<css\s+external-embed=\"(.+?)\"\s*\/\>","i"),
            '<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
        );
        //
        //    <css external-embed>...</css>
        //
        Wow::register(
            Wow::rx("\<css\s+external-embed\s*\>(.+?)\<\/css\>","i"),
            '<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
        );


        //
        //    <js src="js/file.js"/>
        //
        Wow::register(
            Wow::rx("\<js\s+src=\"(.+?)\"\s*\/\>","i"),
            '<script type="text/javascript" src="$1"></script>'
        );
        //
        //    <js>js/file.js</js>
        //
        Wow::register(
            Wow::rx("\<js\>(.+?)\<\/js\>","is"),
            '<script type="text/javascript" src="$1"></script>'
        );


        //
        //    . file notation can be used but is not necessary
        //    <js embed="js/file.js"/>
        //
        Wow::register(
            Wow::rx("\<js\s+embed=\"(.+?)\"\s*\/\>","i"),
            '<script type="text/javascript"><?php echo Core\FileHandler::public(Core\App::location().\'/\'.\'$1\')->read(); ?></script>'
        );
        Wow::register(
            Wow::rx("\<js\s+embed\s*\>(.+?)\<\/js\>","is"),
            '<script type="text/javascript"><?php echo Core\FileHandler::public(Core\App::location().\'/\'.\'$1\')->read(); ?></script>'
        );


        //
        //    <js external-embed="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/>
        //
        Wow::register(
            Wow::rx("\<js\s+external-embed=\"(.+?)\"\s*\/\>","i"),
            '<script type="text/javascript"><?php echo file_get_contents(\'$1\'); ?></script>'
        );
        //
        //    <js external-embed>https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js</js>
        //
        Wow::register(
            Wow::rx("\<js\s+external-embed\s*\>(.+?)\<\/js\>","is"),
            '<script type="text/javascript"><?php echo file_get_contents(\'$1\'); ?></script>'
        );


        //
        //    <url />
        //
        Wow::register(
            Wow::rx("\<url\s*\/\>","i"),
            "<?php echo Core\App::url(); ?>"
        );
        Wow::register(
            Wow::rx("\<url\s+inject\s*\/\>","i"),
            "Core\App::url()"
        );


        //
        //    <app>environment()</app>
        //
        Wow::register(
            Wow::rx("\<app\>(.+?)\<\/app\>", "i"),
            "<?php echo Core\App::$1; ?>"
        );
        Wow::register(
            Wow::rx("\<app\s+inject\s*\>(.+?)\<\/app\>", "i"),
            "Core\App::$1"
        );


        //
        //    <controller name="..." (optional)>COMMAND</controller>
        //
        Wow::register(
            Wow::rx("\<controller\>(.+?)\<\/controller\>","i"),
            "<?php echo Core\ControllerHandler::find()::$1; ?>"
        );
        Wow::register(
            Wow::rx("\<controller\s+name=\"(.+?)\"\s*\>(.+?)\<\/controller\>","i"),
            "<?php echo Core\ControllerHandler::find('$1')::$2; ?>"
        );
        Wow::register(
            Wow::rx("\<controller\s+inject\s*\>(.+?)\<\/controller\>","i"),
            "Core\ControllerHandler::find()::$1"
        );
        Wow::register(
            Wow::rx("\<controller\s+(?:(?:inject\s+name=\"(.+?)\")|(?:name=\"(.+?)\"\s+inject))\s*\>(.+?)\<\/controller\>","i"),
            "Core\ControllerHandler::find('$1')::$2"
        );


        //
        // <view name=".." (optional)>COMMAND</view>
        //
        Wow::register(
            Wow::rx("\<view\>(.+?)\<\/view\>", "i"),
            "<?php echo Core\ViewHandler::find()->$1; ?>"
        );
        Wow::register(
            Wow::rx("\<view\s+name=\"(.+?)\"\s*\>(.+?)\<\/view\>", "i"),
            "<?php echo Core\ViewHandler::find('$1')->$2; ?>"
        );
        Wow::register(
            Wow::rx("\<view\s+inject\s*\>(.+?)\<\/view\>", "i"),
            "Core\ViewHandler::find()->$1"
        );
        Wow::register(
            Wow::rx("\<view\s+(?:(?:inject\s+name=\"(.+?)\")|(?:name=\"(.+?)\"\s+inject))\s*\>(.+?)\<\/view\>", "i"),
            "Core\ViewHandler::find('$1')->$2"
        );


        //
        //    <spoof method="DELETE" />
        //
        Wow::register(
            Wow::rx("\<spoof\s+method=\"([a-zA-Z]+)\"\s*\/?\>","i"),
            '<input type="hidden" name="_method" value="$1" />'
        );


        //
        //    <formtoken name="..." />
        //
        Wow::register(
            Wow::rx("\<formtoken\s+name=\"(.+?)\"\s*\/?\>", "i"),
            '<input type="hidden" data-component="token.$1" name="token-$1" value="<?php echo Core\FormTokenHandler::generate("$1"); ?>" />'
        );


        //
        //    <formtoken name="..." />
        //
        Wow::register(
            Wow::rx("\<formtoken\s+(?:(?:persistent\s+name=\"(.+?)\")|(?:name=\"(.+?)\")\s+persistent)\s*\/?\>", "i"),
            '<input type="hidden" data-component="token.$1" name="token-$1" value="<?php echo Core\FormTokenHandler::persistent("$1"); ?>" />'
        );
    }
    if(Wow::flavor()>=Wow::COMBINED) {
        //
        //    @echo:$username
        //
        Wow::register(
            Wow::rx("@echo:(.+?);", "i"),
            "<?php echo $1; ?>"
        );


        //
        //    @php:
        //
        //        echo 'ok';
        //
        //    @endphp
        //
        Wow::register(
            Wow::rx("@php:(.+?)@endphp", "is"),
            "<?php $1 ?>"
        );


        //
        //    Used to end statements such as if, foreach, ...
        //    @end
        //
        Wow::register(
            Wow::rx("@end", "is"),
            "<?php } ?>"
        );


        //
        //    @if:true
        //        <p>ok</p>
        //    @end
        //
        Wow::register(
            Wow::rx("@if:(.+)", "i"),
            "<?php if($1) { ?>"
        );


        //
        //    Can only be used after an @if before the @end
        //    @elseif:true
        //        <p>ok</p>
        //
        Wow::register(
            Wow::rx("@elseif:(.+)", "i"),
            "<?php } elseif($1) { ?>"
        );


        //
        //    Can only be used after an @if before the @end
        //    @else
        //        <p>ok</p>
        //
        Wow::register(
            Wow::rx("@else", "i"),
            "<?php } else { ?>"
        );


        //
        //    @for:$i=0;$i<10;++$i
        //        <li></li>
        //    @end
        //
        Wow::register(
            Wow::rx("@for:(.+)", "i"),
            "<?php for($1) { ?>"
        );


        //
        //    @foreach:$items as $item
        //        <li></li>
        //    @end
        //
        Wow::register(
            Wow::rx("@foreach:(.+)", "i"),
            "<?php foreach($1) { ?>"
        );


        //
        //    @while:$i<10
        //        <li></li>
        //    @end
        //
        Wow::register(
            Wow::rx("@while:(.+)", "i"),
            "<?php while($1) { ?>"
        );


        //
        //    @css:/css/style.css
        //
        Wow::register(
            Wow::rx("@css:(.+)", "i"),
            '<link rel="stylesheet" type="text/css" href="$1">'
        );


        //
        //    @css_embed:css.style.css
        //
        Wow::register(
            Wow::rx("@css_embed:(.+)", "i"),
            '<style type="text/css"><?php echo Core\FileHandler::public(Core\App::location().\'/\'.\'$1\')->read(); ?></style>'
        );


        //
        //    @css_external:http://....js
        //
        Wow::register(
            Wow::rx("@css_external:(.+)", "i"),
            '<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
        );


        //
        //    @js:js/file.js
        //
        Wow::register(
            Wow::rx("@js:(.+)", "i"),
            '<script type="text/javascript" src="$1"></script>'
        );


        //
        //    @js_embed:js.file.js
        //
        Wow::register(
            Wow::rx("@js_embed:(.+)", "i"),
            '<script type="text/javascript"><?php echo file_get_contents(Core\App::location().\'/\'.\'$1\'); ?></script>'
        );


        //
        //    @js_external:js.file.js
        //
        Wow::register(
            Wow::rx("@js_external:(.+)", "i"),
            '<script type="text/javascript"><?php echo Core\FileHandler::public(\'$1\')->read(); ?></script>'
        );


        //
        //    @url
        //
        Wow::register(
            Wow::rx("@url", "i"),
            '<?php echo Core\App::url(); ?>'
        );
        Wow::register(
            Wow::rx("@iurl", "i"),
            'Core\App::url()'
        );


        //
        //    @app:getDevelopmentStatus()
        //
        Wow::register(
            Wow::rx('@app:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            '<?php echo Core\App::$1; ?>'
        );
        Wow::register(
            Wow::rx('@iapp:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            'Core\App::$1'
        );


        //
        //    @controller:method()
        //
        Wow::register(
            Wow::rx('@controller:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "<?php echo Core\ControllerHandler::find()::$1; ?>"
        );
        Wow::register(
            Wow::rx('@icontroller:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "Core\ControllerHandler::find()::$1"
        );


        //
        //    @controller_Pages.HomeController:method()
        //
        Wow::register(
            Wow::rx('@controller_(.+?):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "<?php echo Core\ControllerHandler::find('$1')::$2; ?>"
        );
        Wow::register(
            Wow::rx('@icontroller_(.+?):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "Core\ControllerHandler::find('$1')::$2"
        );


        //
        //    @view:method()
        //
        Wow::register(
            Wow::rx('@view:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "<?php echo Core\ViewHandler::find()->$1; ?>"
        );
        Wow::register(
            Wow::rx('@iview:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "Core\ViewHandler::find()->$1"
        );


        //
        //    @view_index:method()
        //
        Wow::register(
            Wow::rx('@view_('.Wow::PHP_NAME_RX.'):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "<?php echo Core\ViewHandler::find('$1')->$2; ?>"
        );
        Wow::register(
            Wow::rx('@iview_('.Wow::PHP_NAME_RX.'):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
            "Core\ViewHandler::find('$1')->$2"
        );


        //
        //    @spoof_method:DELETE
        //
        Wow::register(
            Wow::rx("@spoof_method:([a-zA-Z]+)","i"),
            '<input type="hidden" name="_method" value="$1" />'
        );


        //
        //    @formtoken:name
        //
        Wow::register(
            Wow::rx('@formtoken:([a-zA-Z0-9\_\-]+)', "i"),
            '<input type="hidden" name="token-$1" value="<?php echo Core\FormTokenHandler::generate("$1"); ?>" />'
        );


        //
        //    @formtoken_persistent:name
        //
        Wow::register(
            Wow::rx('@formtoken_persistent:([a-zA-Z0-9\_\-]+)', "i"),
            '<input type="hidden" name="token-$1" value="<?php echo Core\FormTokenHandler::persistent("$1"); ?>" />'
        );
    }

