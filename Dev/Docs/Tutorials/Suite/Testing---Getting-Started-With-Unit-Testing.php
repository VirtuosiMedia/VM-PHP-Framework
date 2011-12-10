<h1>How To Use The VM Framework Test Suite</h1>
<p>This brief tutorial will teach you how to write unit tests for your own application using the VM Framework Test Suite. The VM Framework Test Suite was created to make unit testing a PHP application as simple and easy as possible so you can use Test Driven Development (TDD) practices without a lot of hassle or setup. </p>
<h2>Unit Testing Overview</h2>
<p>Before diving into how to write a unit test using the VM Framework Test Suite, let's first take a look at the practice of developing with unit tests and why they're useful.</p>
<p>Unit tests are meant to improve the stability and security of your application by testing each part of it individually. If all of your tests pass, you know that your application is running as it should be. However, if one or more of your tests fail, you not only know that you application is broken, you also know exactly where to look. This is especially useful when adding new functionality to your application or making sure that an bug fix doesn't have unintended consequences elsewhere.</p>
<p>Each unit test should test a single method of a single class for a specific piece of information. If you develop with this in mind from the beginning, your codebase will become more stable and modular, resulting in cleaner code.</p>
<p>Unit testing doesn't solve every software problem, but it's an incredibly useful tool to spot when your application breaks. While your goal should be to get as much test coverage as possible, some parts of an application are better candidates to unit test than others.</p>
<p>In a typical Model-View-Controller (MVC) application, unit tests make more sense for the Model and Controllers, where the codebase won't often change, but less sense for the View, which tends to change a lot. Similarly, unit testing your database may be more trouble than it's worth. Focus on what's imporant: test what needs to be stable and shouldn't change very often rather than trying to test fluid code.</p>
<p>Here are some general best practices for developing with unit tests:</p>
<ul>
	<li>Tests should be simple. Test the smallest unit of information or code possible.</li>
	<li>Write the test before your code.</li>
	<li>Write your code so that it first fails the test. Then fix it to pass.</li>
	<li>Each unit test should test a single method for a specific piece of information.</li>
	<li>Test public methods and leave the private methods alone as they can change.</li>
	<li>Multiple tests may be required per method. Write a test that covers each use case of that method.</li>
	<li>Unit tests should be descriptively but succinctly named according to what they test.</li>
	<li>If you find a bug, write a test that tests for the presence of the bug before fixing it.</li>
	<li>When you update your code, be sure to update your tests at the same time.</li>
</ul>
<p>There is far more to unit testing than just this short overview, but it should give you a general knowledge about unit testing and how it's used.</p>
<h2>Writing Unit Tests With The VM Framework Test Suite</h2>
<p>In order to begin writing unit tests for you application with the VM Framework Test Suite, you'll first need to install the framework. VM Framework will then auto-generate a code skeleton for your application, including your application folder, which you can find in the <i>includes</i> directory. When using VM Framework to develop your application, most of your application code should be contained in your application folder.</p>
<h3>Test Naming Conventions</h3>
<p>Every folder that you create in the <i>includes</i> directory should also be created in the <i>Tests</i> directory. Likewise, every PHP application file that you'd like to test should also be mirrored by a file of the same name in the <i>Tests</i> directory. The only difference in name will be that you need to add the word 'Test' to the end of your filename.</p>
<p>Let's look at a quick example. Suppose you're developing a teaching app called <b>Schoolar</b> and your application folders and files look like this:</p>
<ul>
	<li>includes
		<ul>
			<li>Schoolar
				<ul>
					<li>Lessons
						<ul>
							<li>Logic.php</li>
							<li>Math.php</li>
							<li>Spelling.php</li>
						</ul>
					</li>
				</ul>
			</li>
			<li>Vm</li>
		</ul>
	</li>
</ul>
<p>With the above directory and file structure, your <i>Tests</i> directory should look like this:</p>
<ul>
	<li>Tests
		<ul>
			<li>Schoolar
				<ul>
					<li>Lessons
						<ul>
							<li>LogicTest.php</li>
							<li>MathTest.php</li>
							<li>SpellingTest.php</li>
						</ul>
					</li>
				</ul>
			</li>
			<li>Vm</li>
		</ul>
	</li>
</ul>
<p><b>Note</b>: It's important that each of your application file names is capitalized to match the VM Framework naming convention. Failure to capitalize the file names could result in your application not working on Linux servers.</p>
<h3>Writing Your First Test Class</h3>
<p>Since we're going to write our first test class simultaneously with our application class, let's start with the <i>Math.php</i> class as it's an easy example.</p>
<p>With VM Framework, your class names should always reflect your directory structure so that the autoloader can load the proper classes. In our example, the class contained in <i>Math.php</i> would be called <b><i>Schoolar_Lessons_Math</i></b> and the test class contained in <i>MathTest.php</i> would be titled <b><i>Tests_Schoolar_Lessons_MathTest</i></b>.</p>
<p>The <b><i>Schoolar_Lessons_Math</i></b> class is going to perform a few very basic mathematical functions, with methods called <i>add</i>, <i>subtract</i>, <i>multiply</i>, and <i>divide</i>.</p>
<p>Every test that you write should extend the <i>Test</i> class found in the <i>Tests</i> directory. In this case, your test will start out looking like the class below:</p>
<pre class="php">
class Tests_Schoolar_Lessons_MathTest extends Tests_Test {

	protected function setUp(){
	
	}

}
</pre>
<p>The <i>setUp</i> method is called automatically by the parent class before each test is run. There is also method called <i>tearDown()</i> that is executed after each test. While both methods are optional, they are incredibly useful as shortcuts and can save you a lot of typing if your tests are similar.</p>
<p>In general, the <i>setUp</i> method should be used to instantiate the class you are testing. The parent test class has a property called <i>$fixture</i> that is reserved to refer to the instantiated class, like so:</p>
<pre class="php">
class Tests_Schoolar_Lessons_MathTest extends Tests_Test {

	protected function setUp(){
		$this-&gt;fixture = new Schoolar_Lessons_Math();
	}

}
</pre>
<p>Now, instead of manually creating a new instance of your class for each test, it's handled automatically. You can refer to the object by simply calling <i>$this-&gt;fixture.</i></p>
<p>Let's create our first unit test for the <i>Schoolar_Lessons_Math</i> class. The VM Framework Test Suite uses assertions to test class methods. There are several different types of assertion methods available and the first one we're going to look at is called <i>assertEqual</i>, which simply accepts two parameters and tests if they are equal to each other. If they are equal, the test passes, otherwise, the test fails.</p>
<pre class="php">
class Tests_Schoolar_Lessons_MathTest extends Tests_Test {

	protected function setUp(){
		$this-&gt;fixture = new Schoolar_Lessons_Math();
	}

	protected function testAddTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(2, 2), 4);
	}
}
</pre>
<p>In the example above, we're feeding 2+2 into the <i>Schoolar_Lessons_Math</i> class' <i>add</i> method and testing to make sure it equals 4. Two things to note: <b>1)</b> You must always return your assertion or the test will fail; <b>2)</b> Try to make your test method name descriptive.</p>
<p>At this point, if we were to run our test suite, it would result in a 'Class not found' error as we haven't yet created our <i>Schoolar_Lessons_Math</i> class. Let's do that now.</p>
<pre class="php">
class Schoolar_Lessons_Math {

	public function add($number1, $number2){
		return $number1 - $number2;
	}
}
</pre>
<p>All of your methods that you are testing must be public, else you won't be able to access them to test them. If you take a close look at the <i>add</i> method above, you'll notice that I'm not adding two numbers, I'm subtracting the second number from the first. If you were to run your test suite now, you'd see that the test would fail. Go ahead and fix it and then reload your test suite.</p>
<pre class="php">
class Schoolar_Lessons_Math {

	public function add($number1, $number2){
		return $number1 + $number2;
	}
}
</pre>
<p>Great! Now your test should pass. The reason why you should always endeavor to write your code to fail first is to make sure that you're actually testing what you intended to test.</p>
<p>Did you notice how easy it was setup your test suite? You don't need to maintain a list of test classes or individual tests. All you need to do is create a test class in the right folder with the right name and the VM Framework Test Suite will detect it automatically. What's more, you only need to reload the test suite page to run your test suite. Simple, effective, and fast.</p>
<p>Because there are still a few ways that the <i>add</i> method could be used, let's create a few more test methods for it.</p>
<pre class="php">
class Tests_Schoolar_Lessons_MathTest extends Tests_Test {

	protected function setUp(){
		$this-&gt;fixture = new Schoolar_Lessons_Math();
	}

	protected function testAddTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(2, 2), 4);
	}
	
	protected function testAddTwoNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(-2, -2), -4);
	}
	
	protected function testAddPositiveNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(2, -1), 1);
	}	
}
</pre>
<p>If you refresh the test suite, you'll see that your new tests have appeared. Now let's quickly test the other methods in the class. First, the completed test class:</p>
<pre class="php">
class Tests_Schoolar_Lessons_MathTest extends Tests_Test {

	protected function setUp(){
		$this-&gt;fixture = new Schoolar_Lessons_Math();
	}

	protected function testAddTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(2, 2), 4);
	}
	
	protected function testAddTwoNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(-2, -2), -4);
	}
	
	protected function testAddPositiveNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;add(2, -1), 1);
	}

	protected function testSubtractTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;subtract(3, 2), 1);
	}

	protected function testSubtractTwoNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;subtract(-3, -2), -1);
	}

	protected function testSubtractPositiveNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;subtract(3, -2), 5);
	}

	protected function testMultiplyTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;multiply(3, 2), 6);
	}

	protected function testMultiplyTwoNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;multiply(-3, -2), 6);
	}

	protected function testMultiplyPositiveNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;multiply(3, -2), -6);
	}

	protected function testDivideTwoPositiveNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;divide(6, 2), 3);
	}

	protected function testDivideTwoNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;divide(-6, -2), 3);
	}

	protected function testDividePositiveNegativeNumbers(){
		return $this-&gt;assertEqual($this-&gt;fixture-&gt;divide(6, -2), -3);
	}	
}
</pre>
<p>And here is the complete <i>Schoolar_Lessons_Math</i> class:</p>
<pre class="php">
class Schoolar_Lessons_Math {

	public function add($number1, $number2){
		return $number1 + $number2;
	}
	
	public function subtract($number1, $number2){
		return $number1 - $number2;
	}
	
	public function multiply($number1, $number2){
		return $number1 * $number2;
	}

	public function divide($number1, $number2){
		return $number1 / $number2;
	}	
}
</pre>
<p>While you could certainly come up with additional tests for the <i>Schoolar_Lessons_Math</i> class, the above tests should provide a good testing base for the class. If you make changes to the class, you'll know instantly if your changes have broken anything.</p>
<p>We won't cover creating tests for the other classes in our fictional <i>Schoolar</i> app, but if it were a real application, you would create a test class for every application class, with one or more tests testing the application class' methods.</p>
<h3>Unit Testing Assertion Methods</h3>
<p>Until now, we've only covered the use of the <i>assertEqual</i> assertion method, but the VM Framework Test Suite offers a total of 16 assertions that you can use in your unit tests. Let's take a look at each of them in turn.</p>
<ul>
	<li><i>assertEqual</i> - ($x, $y) [mixed, mixed] Tests if x is equal to y, fails if they are not equal.</li>
	<li><i>assertEqualStrict</i> - ($x, $y) [mixed, mixed] Tests if x is equal to y with type comparison, fails if they are not equal.</li>
	<li><i>assertNotEqual</i> - ($x, $y) [mixed, mixed] Tests if x is not equal to y, fails if they are equal.</li>
	<li><i>assertNotEqualStict</i> - ($x, $y) [mixed, mixed] Tests if x is not equal to y with type comparison, fails if they are equal.</li>
	<li><i>assertGreaterThan</i> - ($x, $y) [num, num] Tests if x is greater than y, fails if x is less than or equal to y.</li>
	<li><i>assertGreaterThanOrEqual</i> - ($x, $y) [num, num] Tests if x is greater than or equal to y, fails if x is less than y.</li>	
	<li><i>assertLessThan</i> - ($x, $y) [num, num] Tests if x is less than y, fails if x is greater than or equal to y.</li>
	<li><i>assertLessThanOrEqual</i> - ($x, $y) [num, num] Tests if x is less than or equal to y, fails if x is greater than y.</li>		
	<li><i>assertTrue</i> - ($x) [boolean] Tests if x is TRUE, fails if it is not.</li>
	<li><i>assertFalse</i> - ($x) [boolean] Tests if x is FALSE, fails if it is not.</li>
	<li><i>assertNull</i> - ($x) [mixed] Tests if x is NULL, fails if it is not.</li>
	<li><i>assertNotNull</i> - ($x) [mixed] Tests if x is not NULL, fails if it is.</li>
	<li><i>assertIsEmpty</i> - ($x) [mixed] Tests if x is empty, fails if it is not.</li>
	<li><i>assertNotEmpty</i> - ($x) [mixed] Tests if x is not empty, fails if it is.</li>
	<li><i>assertResourceExists</i> - ($resourceName) [string] Tests if a file or directory exists, fails if it does not exist.</li>
	<li><i>assertResourceDoesNotExist</i> - ($resourceName) [string] Tests if a file or directory does not exist, fails if it does exist.</li>	
</ul>
<h4>How To Create Your Own Assertions</h4>
<p>Because the above assertion methods won't cover every testing scenario, it was important to make adding new test assertions types easy.</p>
<p>The VM Framwork Test Suite uses a separate class for each type of assertion, even though the assertions are called as methods. The classes are automagically loaded when you call the assertion method. Because of this, adding a new assertion simply requires creating a new assertion class that extends the base <i>Tests_Test_Assert</i> class.</p>
<p>The <i>Tests_Test_Assert</i> class contains only one method, <i>getResult()</i>, which returns TRUE if the test passed and FALSE if it failed. <i>Tests_Test_Assert</i> also contains a single property, <i>$result</i>, which contains the value returned by <i>getResult()</i>.</p>
<p>When you create a new assertion, it must give <i>$result</i> a value of either TRUE or FALSE else the test won't work. The value must be set automatically by either the constructor method or a method called by the constructor.</p>
<p>All new assertions must be contained in the <i>Tests/Test/Assert/</i> folder and should be named according to the same naming conventions used in the unit tests for autoloading purposes.</p>
<p>Since we've already been using it in our examples above, let's dissect the <i>Tests_Test_Assert_Equal</i> class for our example here:</p>
<pre class="php">
class Tests_Test_Assert_Equal extends Tests_Test_Assert {
	
	/**
	 * Tests if x is equal to y, fails if they are not equal
	 * @param mixed $x - The first parameter
	 * @param mixed $y - The second parameter
	 */
	function __construct($x, $y){
		$this-&gt;result = ($x == $y) ? TRUE : FALSE;
	}	
}
</pre>
<p>As you can see, the constructor method compares two parameters to see if they are equal to each other and then sets the value of the $result property. So when you call the <i>assertEqual</i> method in your unit test class, the <i>Tests_Test_Assert_Equal</i> class is instantiated and its constructor is passed the method parameters. You can have between 0 and 4 parameters that you pass through to the constructor.</p>
<p>Adding a new assertion is easy, just create a new file in the <i>Tests/Test/Assert/</i> folder, copy one of the existing tests into the file, modify it, and then begin using it as a method in your test class.</p>
<h2>Conclusion</h2>
<p>In this tutorial, you've learned about the benefits of unit testing, when to use it, and how to write tests using the VM Framework Test Suite. You've also learned how to extend the test suite with additional types of tests, should you need them.</p>
<p>As a result of unit testing, your application should be more stable and modular while incurring less technical debt than it would have without testing. From all of us here at Virtuosi Media, thanks for using VM Framework. Best of luck developing your application and happy testing!</p>