
{include file="empathy_header.tpl"}


{if $class eq 'demo'}

<h2>Countries and Country Codes (ISO 3166)</h2>
<ul>
{foreach from=$countries item=i key=k}
<li>{$i} ({$k})</li>
{/foreach}
</ul>

{else}

<h1>My framework, Empathy</h1>
<p>Empathy is a lightweight MVC framework for developing applications in PHP5 greatly inspired by Joe Stumps articles on the subject found
<a href="http://www.onlamp.com/pub/a/php/2005/09/15/mvc_intro.html">here</a>.  The project has come about through an urge to have in place an MVC type platform that I can understand, while moving my attentions into investigating other web development technologies including other frameworks. Empathy is not meant to be the ultimate solution. Merely what works for me and maybe you too, to some extent.</p>

<h2>Conventions</h2>
<ul>
<li>...</li>
</ul>

<h2>Demos</h2>
<ul>
<li>General demo. Access a list of countries parsed from a text file. (As if accessing a database entity type class.)
<a href="http://{$WEB_ROOT}{$PUBLIC_DIR}/empathy/demo">Go</a></li>
<li>Simulate an error. <a href="http://{$WEB_ROOT}{$PUBLIC_DIR}/empathy/demo/sim_error">Go</a></li>
</ul>

{/if}


{include file="empathy_footer.tpl"}