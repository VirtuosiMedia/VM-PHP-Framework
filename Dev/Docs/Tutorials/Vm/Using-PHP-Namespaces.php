<h1>Using PHP Namespaces</h1>
<p>Namespaces are a relatively new feature for PHP and because VM PHP Framework makes extensive use of them, it's 
	prudent to address some best practices for using namespaces in your own code. First, though, let's talk about what
	namespaces actually are.</p>
<p>The easiest way to visualize namespaces is to think of them as shelves that contain collections of books (your 
	classes). Instead of having your books strewn all over the room, unorganized and hard to find, the books are placed
	neatly on shelves, grouped according to topic. What's more, books in different topical sections can share the same
	name without confusion - all you need to do is specify that you're talking about children's books when you mention
	<i>Green Eggs and Ham</i> and the librarian will know that you're talking about the Dr. Seuss book rather than a
	like-named cookbook.</p>
<p>In fact, namespaces also allow you to refer to your book by a different name or description. You 
	know, that <i>blue</i> book? The book that I read last summer? That lost manuscript by Frank Herbert that they just
	found? In each case, we referred to a specific book without naming its exact title. Namespaces allow us to do the 
	same thing with our code through a process called aliasing.</p> 
<h2>Why Namespaces?</h2>
<p>If you're familiar with the Zend or PEAR class naming conventions, you might wonder why namespaces are necessary at
	all. What problems do they solve?</p>
<p>For most modern PHP projects that use one of the naming conventions above, the switch to namespaces will provide 
	several small but real benefits over the previous way of doing things:</p>
<ul>
	<li><strong>Eliminate Naming Conflicts</strong> - Classes that share the same name and are loaded into the same page
		will cause an error. On small projects, this isn't usually a problem, but on larger projects with multiple 
		libraries, naming conflicts can appear.</li>
	<li><strong>Aliased Class Instantiation</strong> - If you have a long class name like 
		<i>Widget_Testing_Foo_Bar_Baz</i>, you can shorten it using a namespace alias so that a new instantiation would
		look like <i>new Bar\Baz();</i> rather than <i>new Widget_Testing_Foo_Bar_Baz();</i>. As a result, your code is 
		more succinct and readable.</li>
	<li><strong>Future Compatability</strong> - More and more PHP projects will begin using namespaces in the coming 
		years and by implementing them now, you'll save yourself the effort of converting your code later.</li>
</ul>
<p>If you haven't been working with the Zend or PEAR naming conventions, switching to namespaces will also offer the 
	benefit of better code organization.</p>
<h2>Basic Usage</h2>
<p>Though you can also use namespaces with constants and functions, for the purposes of this tutorial, we'll only
	cover using namespaces with classes.</p>
<h3>Declaring A Namespace</h3>
<p>The syntax for PHP namespaces isn't beautiful, but it's straightforward. Other than comments or whitespace, your
	namespace declaration must be the first code in your file, even before your class declaration.</p>
<pre class="php">
/**
 * Some DocBloc comments here
 */
 
namespace Vm;

class Widget {

	public function __construct(){
		echo 'Hi!';
	}
}
</pre>
<p>The above is an example of a single namespace, but you can also have sub-namespaces.</p>	
<pre class="php">
namespace Vm\Widget;

class Gizmo {

	public function __construct(){
		echo 'Gizmo says "Hi!"';
	}
}
</pre>
<p>In this case, we indicated a sub-namespace by using a backslash, which is the namespace separator. You can have 
	multiple sub-namespaces, but you cannot nest one namespace declaration inside another.</p>
<p>And, of course, multiple classes can share a namespace.</p>
<pre class="php">
namespace Vm\Widget;

class Doohicky {

	public function __construct(){
		echo 'Doohicky says "Hi!"';
	}
}
</pre>
<h3>Class Instantiation And Importing Namespaces</h3>
<p>There are a few ways to instantiate classes using namespaces depending on your use case. We'll start with the most 
	simple example and work our way forward. In all examples, assume that the current namespace is the global 
	namespace.</p>
<pre class="php">
include('Vm/Widget.php');
$widget = new Vm\Widget();
</pre>
<p>In this first example, the class is instantiated using the full namespace combined with the class name. If you've 
	used the PEAR or Zend naming conventions in the past, the only difference here is that the separator is a backslash 
	rather than an underscore.</p>
<p>However, suppose that the Widget class is instantiated multiple times over the course of the script. Rather than 
	typing the full namespace each time we use the Widget class, we can import the namespace and use an abbreviated
	reference to instantiate the class. Importing a namespace <strong>does not</strong> mean that any files are 
	autoloaded, rather it means that the references to class is prepended with the namespace automatically.</p>
<p>Importing a namespace is done with the <i>use</i> operator, followed by the namespace name and the class name, 
	separated by the backslash.</p>
<pre class="php">
include('Vm/Widget.php');

use Vm\Widget;

$widget1 = new Widget();
$widget2 = new Widget();
</pre>
<p>In the example above, both the Vm namespace and the name of the Widget class are specified in the use declaration. 
	Because	of this, the Widget class can be instantiated simply: new Widget(). However, once the Gizmo and Doohicky 
	classes are introduced to the script, the namespacing becomes a little more complex.</p>
<pre class="php">
include('Vm/Widget.php');
include('Vm/Widget/Gizmo.php');
include('Vm/Widget/Doohicky.php');

use Vm\Widget;

$widget = new Widget();
$gizmo = new Widget\Gizmo();
$doohicky = new Widget\Doohicky();
</pre>	
<p>Because there is a class named Widget <i>and</i> a namespace named Widget, the import declaration is now doing 
	double-duty. It shortcuts the reference to the Widget class <i>and</i> it also abbreviates the full namespace for 
	the Gizmo and Doohicky classes. If you were to simply declare <i>new Gizmo();</i> rather than new 
	<i>new Widget\Gizmo();</i>, the script would result in an error because it wouldn't be able to find a class named
	Gizmo in the Vm namespace. If the class that you're instantiating isn't in the current namespace, you must prepend 
	the class name with the namespace relative to the current namespace. Note that this only works if the relative 
	namespace is a sub-namespace of the current namespace, otherwise you must use the full namespace.</p>
<p>In order to shortcut multiple classes, you must import multiple namespaces.</p>
<pre class="php">
include('Vm/Widget.php');
include('Vm/Widget/Gizmo.php');
include('Vm/Widget/Doohicky.php');

use Vm\Widget;
use Vm\Widget\Gizmo;
use Vm\Widget\Doohicky;

$widget = new Widget();
$gizmo = new Gizmo();
$doohicky = new Doohicky();
</pre>
<h3>Aliasing</h3>
<p>For long namespaces, it can useful to provide an alias as a reference to the namespace.</p>
<pre class="php">
include('Vm/Widget.php');
include('Vm/Widget/Gizmo.php');
include('Vm/Widget/Doohicky.php');

use Vm\Widget as Wgt;

$widget = new Wgt();
$gizmo = new Wgt\Gizmo();
$doohicky = new Wgt\Doohicky();
</pre>
<p>As you can see, our import declaration is similar to those previous, but also contains the <i>as</i> keyword, 
	followed by the alias name.</p>
<div class="note"><span></span><p>Even though you can specify single-letter aliases, you should use aliases that have
	meaning and are unambiguous. Completely sacrificing clarity and readability for the sake of brevity isn't a good 
	trade-off, especially when it comes time to maintain your code.</p></div>
<h3>Class Inheritance Using Namespaces</h3>
<p>Class inheritance with namespaces is similar to inheritance without namespaces, except that you need to write out 
	the namespace of the class that is being extended.</p>
<pre class="php">
namespace Vm\Widget;

class Gadget extends Vm\Widget {}
</pre>	  
<p>There is, however, one gotcha to the extension. The above code snippet will actually result in an error when the 
	class is instantiated because it assumes that the Widget class is found in the Vm\Widget\Vm namespace rather than
	the Vm namespace.</p>
<p>The reason this happens is because a namespace has already been declared on the first line, so
	the parent class' namespace is assumed to be relative to the declared namespace. To solve this problem, simply use 
	a leading backslash to indicate the global namespace.</p>
<pre class="php">
namespace Vm\Widget;

class Gadget extends \Vm\Widget {}
</pre>		
<h3>One Class Per File</h3>
<p>The standard way to use namespaces is to have a 1-to-1 correspondance between your classes and your files. Your
	namespaces should also directly correspond to your directory structure. For example, suppose you have the following
	class and directory structure inside a folder called Vm:</p>
<ul>
	<li><strong>Filter</strong>
		<ul>
			<li>Lower.php</li>
			<li>StripTags.php</li>
			<li>Upper.php</li>
		</ul>
	</li>
	<li><strong>Validate</strong>
		<ul>
			<li><strong>Credit</strong>
				<ul>
					<li>Amex.php</li>
					<li>Discover.php</li>
					<li>Mastercard.php</li>
					<li>Visa.php</li>
				</ul>
			</li>
			<li>Credit.php</li>
			<li>Email.php</li>
			<li>Url.php</li>
		</ul>
	</li>
	<li>Filter.php</li>
	<li>Validate.php</li>
	<li>View.php</li>
</ul>
<p>Right away, you should notice that we have some classes that share names with folders. When this happens, the class 
	should be placed in the namespace that corresponds to the directory in which it resides. This means that the class
	contained in the Filter.php file belongs in the <i>\Vm</i> namespace and not the <i>Vm\Filter</i> namespace.</p>
<p>Here is how we would instantiate a few of the above classes:</p>
<pre class="php">
$filter = new Vm\Filter();
$lower = new Vm\Filter\Lower(); //Here, Filter is part of the namespace.
$amex = new Vm\Validate\Credit\Amex(); 
</pre>
<h3>Autoloading</h3>
<p>PHP provides an extremely useful function in the <i>autoload</i> function. Rather than having to litter your code 
	with multiple includes, you can have each file loaded automatically at runtime once a class is instantiated. The 
	one-class-per-file pattern the mirrors the directory structure makes autoloading extremely easy to accomplish. VM 
	PHP Framework relies heavily on the autoloader, but you should be aware of how it works on a basic level.</p>
<pre class="php">
function __autoload($class){
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	require_once($class.'.php');	
}
</pre>
<p>The above autoload function replaces the backslash of the namespace with the directory separator and simply loads 
	the required class. It will allow for the following code, which autoloads our classes from above:</p>
<pre class="php">
include('autoload.php');

use Vm\Widget;
use Vm\Widget\Gizmo;
use Vm\Widget\Doohicky;

$widget = new Widget();
$gizmo = new Gizmo();
$doohicky = new Doohicky();
</pre>
<h2>Namespace Best Practices</h2>
<p>VM PHP Framework conforms to several best practices when it comes to namespacing and recommends that you do the same
when writing your own code.</p>
<ul>
	<li><strong>PSR-0</strong> - The PHP Standards group has proposed a naming standard for autoloader interoperability,
		<a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md">PSR-0</a>, with which VM PHP 
		Framework is compliant.</li>
	<li><strong>Direct Namespace/Directory Correlation</strong> - Namespaces will directly reflect the directory 
		structure on a one-to-one basis.</li>
	<li><strong>One Class Per File</strong> - As detailed above, each file contains only a single class.</li>
	<li><strong>Pascal Case</strong> - Each word in a namespace or class name capitalizes the first letter. As an 
		example, Vm\Widget\Gizmo and Vm\Widget\SuperGizmo are valid whereas vm\Widget\Gizmo, Vm\Widget\superGizmo, and
		vm\widget\gizmo are not.</li>
	<li><strong>Semantic Aliases</strong> - If and when namespace aliases are used, they must have a clear, semantic, 
		and unambigous meaning.</li>
	<li><strong>Restrained Importing</strong> - Minimize the use of the namespace importing so that the top of a file 
		isn't cluttered. If more than two classes are specifically imported with the use operator from the same 
		namespace, go back a namespace and use more specificity when the classes are instantiated.</li>
</ul>
<p>As an example of the last point, if you have the following code:</p>
<pre class="php">
include('autoload.php');

use Vm\Widget\Gadget;
use Vm\Widget\Gizmo;
use Vm\Widget\Doohicky;

$widget = new Gadget();
$gizmo = new Gizmo();
$doohicky = new Doohicky();
</pre>
<p>Rather than cluttering the top of the file with import statements, the code should be refactored. Because each of the 
	classes above share the Widget namespace, the shared namespace should be imported if there are more than two classes 
	being imported by name. The revised code would look like:</p>
<pre class="php">
include('autoload.php');

use Vm\Widget;

$widget = new Widget\Gadget();
$gizmo = new Widget\Gizmo();
$doohicky = new Widget\Doohicky();
</pre>
<h2>Namespace Gotchas</h2>
<p>While namespaces do offer a number of advantages, there are a few instances where issues can arise. If you are aware 
	of the areas where namespaces can get tricky, it'll make working with namespaces much easier.</p>
<ul>
	<li><strong>Reserved Words Are Forbidden</strong> - Using a PHP reserved word anywhere in your namespace will 
		result in an error. For a complete list of reserved words, see the 
		<a href="http://php.net/manual/en/reserved.php">PHP Manual</a>.
	</li>
	<li><strong>Dynamic Class Instantiation From A String</strong> - The backslash character is the namespace separator, 
		but it is also an escape character, so you will need to take special care when constructing class names from a 
		string. A string that ends with a <i>\</i> will escape the final quotation mark, continuing the string. Also, 
		any control characters like a tab, <i>\t</i>, or a newline, <i>\n</i>, will be interpreted in a string in 
		double-quotes. To avoid these issues, you must escape the backslash character, <i>\\</i>.
	</li>
	<li><strong>Aliases Don't Always Work As Expected</strong> - If you want to use the	reflection API or other PHP 
		classes or functions that deal with classes, you must use the fully-qualified namespace to refer to the class 
		because aliases won't work.
	</li>
	<li><strong>Native PHP Classes May Need To Be Fully Qualified</strong> - If you aren't in the global namespace, you 
		will need to fully qualify any native PHP classes as PHP isn't smart enough to look for them if they aren't in 
		the current namespace. So, if you aren't in the global namespace, <i>new DOMDocument();</i> needs to become
		<i>new \DOMDocument();</i> or it will result in an error.
	</li>
</ul> 
<h2>Further Reading</h2>
<p>The <a href="http://www.php.net/manual/en/language.namespaces.php">PHP manual</a> has additional extensive 
	documentation on using namespaces including specific gotchas, how namespaces are resolved, and how to use 
	namespaces with other language constructs like functions and constants.</p>