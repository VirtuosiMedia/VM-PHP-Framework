<h1>Vm_Mixer Tutorial</h1>
<p>Vm_Mixer simulates multiple inheritance using mixins. It allows you to extend the functionality of multiple classes 
and manage any conflicts between them, should they arise.</p>
<h2>Example</h2>
<p>Here is a quick example of how this could be used. Suppose that you're creating a game and you have multiple
creatures that all have some forms of weapons and defense. With mixins, you can create separate classes for both weapons
and defense and add them both to your creature classes, along with any other classes you might want to add. The creature
will then mix in all public methods of both classes without directly extending either.</p>
<h3>Mixin 1</h3>
<pre class="php">class Weapons {

    public function laser(){
        echo 'Firing laser!&lt;br/&gt;';
    }

    public function catapult(){
        echo 'Launching catapult! MEEEOOOWWW!&lt;br/&gt;';
    }

    public function explode(){
        echo 'Boom!&lt;br/&gt;';
    }

    public function greet(){
        echo 'Prepare for your doom!&lt;br/&gt;';
    }
}
</pre>
<h3>Mixin 2</h3>
<pre class="php">
class Defense {

    public function shield(){
        echo 'Enabling shield!&lt;br/&gt;';
    }

    public function invisibility(){
        echo 'Activating invisibility cloak!&lt;br/&gt;';
    }

    public function explode(){
        echo 'Self destruct sequence activated!&lt;br/&gt;';
    }

    public function serve(array $foodItems){
        echo '#Sets table with '.implode(', ', $foodItems).'#&lt;br/&gt;';
    }
}
</pre>
<h3>Robot Class!</h3>
<p>Finally, we come to our creature class, in this case, a robot. The robot will make use of both mixins and all of the
public mixin methods will be available from any robot object that we instantiate.</p>
<pre class="php">
class Robot extends Vm_Mixer {

    protected $name;

    public function __construct($name){
        $this-&gt;name = $name;
        $this-&gt;addMixin(new Weapons());
        $this-&gt;addMixin(new Defense());

        //The explode method has a conflict as it exists in both Weapons and Defense, so let's give precedence to Defense
        $this-&gt;setPriorities(array('explode'=&gt;'Defense'));
    }

    //Pre-empts the Weapons::greet method
    public function greet(){
        echo "Hi, my name is $this-&gt;name.&lt;/br&gt;";
    }
}
</pre>
<p>Notice that both the weapons and defense classes have an explode method, so we must explicitly state which class'
explode method should be used through the setPriorities method. You can only set a method/class priority once. If two 
classes share a method and no priority is set, the first class passed in as a mixin will be given precedence.</p>
<h3>Usage</h3>
<p>Using our robot class is now incredibly simple. We have access to all of the public mixin methods as if they were 
native methods of the robot class.</p>
<pre class="php">
$robot = new Robot('Robby');
$robot-&gt;greet();
$robot-&gt;shield();
$robot-&gt;laser();
$robot-&gt;explode();
$robot-&gt;serve(array('Pizza', 'Cheeseburgers', 'Egg Nog', 'Vanilla Pudding'));
</pre>
<p>The above will return:</p>
<pre class="php">
Hi, my name is Robby.  
Enabling shield!  
Firing laser!  
Self destruct sequence activated! 
#Sets table with Pizza, Cheeseburgers, Egg Nog, Vanilla Pudding#
</pre>