<h1>Vm_View Tutorial</h1>
<p>Here is a brief tutorial to show you how to use Vm_View:</p>
<pre class="php">
&lt;?php 
$view = new Vm_View();
$view-&gt;setFilters(array(
	'StripTags',
	'Lower',
	'Hyphenate'
));
$view-&gt;title = '&lt;span style="color:red;"&gt;The is a title&lt;/span&gt;';
$view-&gt;message = '&lt;i&gt;This is a message&lt;/i&gt;';
$view-&gt;items = array('Item 1', 'Item 2', 'Item 3');
$view-&gt;loadTemplate('view.php');
</pre>
<p>
Setting the viewspace name essentially acts as namespacing your views (a viewspace). Any view variables 
that are set are placed into the current viewspace and will not be accessible to other viewspaces.
Use the setViewspace method to switch between viewspaces. The default viewspace is 'default'.
</p>
<pre class="php">
$view-&gt;setViewspace('Alternate');
$view-&gt;removeFilters(array('Hyphenate'));
$view-&gt;title = '&lt;span style="color:red;"&gt;The is a second title&lt;/span&gt;';
$view-&gt;message = '&lt;i&gt;This is a second message&lt;/i&gt;';
$view-&gt;items = array('Item 4', 'Item 5', 'Item 6');
</pre>
<p>
If you have an alternate view file you'd like to display, for a template override, as an example,
just specify the alternate directory. If the view file does not exist in the alternate directory,
the view file in the default directory will be loaded. The view filename must be consistent across
all directories else an exception will be thrown. The alternate directory can also be set in the 
constructor.
</p>
<pre class="php">
$view-&gt;loadTemplate('view.php', NULL, 'Alternate/');
//The 'default' viewspace
echo $view-&gt;render();
//The 'Alternate' viewspace
echo $view-&gt;render('Alternate');
?&gt;
</pre>
<p>The first view in the current directory (view.php):</p>
<pre class="php">
&lt;div class="content"&gt;
	&lt;h1&gt;&lt;?php echo $this-&gt;title; ?&gt;&lt;/h1&gt;
	&lt;p&gt;&lt;?php echo $this-&gt;message; ?&gt;&lt;/p&gt;
	&lt;ul&gt;
	&lt;?php foreach($this-&gt;items as $item):?&gt;
		&lt;li&gt;&lt;?php echo $item; ?&gt;&lt;/li&gt;
	&lt;?php endforeach; ?&gt;
	&lt;/ul&gt;
&lt;/div&gt;
</pre>

<p>The alternate view in the Alternate directory (Alternate/view.php):</p>
<pre class="php">
&lt;div class="content"&gt;
	&lt;h1&gt;&lt;?php echo $this-&gt;title; ?&gt;&lt;/h1&gt;
	&lt;h3&gt;This is the alternate view.&lt;/h3&gt;
	&lt;p&gt;&lt;?php echo $this-&gt;message; ?&gt;&lt;/p&gt;
	&lt;ul&gt;
	&lt;?php foreach($this-&gt;items as $item):?&gt;
		&lt;li&gt;&lt;?php echo $item; ?&gt;&lt;/li&gt;
	&lt;?php endforeach; ?&gt;
	&lt;/ul&gt;
&lt;/div&gt;
</pre>
<h2>Using Filters</h2>
<p>You should always escape your output as a security measure, even if it is from the database. Vm PHP Framework
endeavors to make this filtering as painless as possible. Any data assigned to VM_View will automatically be filtered,
so you can set your filters once and use it for all similar data rather than explicitly stating the filters for each
view variable. By default, Vm_View also automatically strips all tags from all output to prevent XSS attacks. (If you 
need HTML in your view content, the strip tags filter can be removed using the <i>removeFilters</i> method.)</p> 
<p>If the filter has required parameters, the filter name should be the key and the parameters should be 
contained in an array, excluding the input parameter, which is automatically included. If there are no 
parameters other than input exist, the filter name should be the array value, not the key.</p>
<pre class="php">
//Filters with params:
$view-&gt;setFilters(array(
	'StripTags'=&gt;array('&lt;i&gt;&lt;b&gt;')
));
 
//Filters without params:
$view-&gt;setFilters(array('StripTags', 'Lower'));

//Mixed:
$view-&gt;setFilters(array(
	'StripTags'=&gt;array('&lt;i&gt;&lt;b&gt;'),
	'Lower',
	'Hyphenate'
));
</pre>
<div class="note"><span></span><p>The <i>setFilters</i> method will override any existing filters, whereas the <i>addFilters</i>
won't.</p></div>