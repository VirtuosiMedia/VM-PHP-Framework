<h1>Vm_Mixer Tutorial</h1>
<p>Vm_Mixer simulates multiple inheritance using mixins. It allows you to extend the functionality of multiple classes 
and manage any conflicts between them, should they arise.</p>
<h1>Example</h1>
<p>Here is a quick example of how this could be used.</p>
<h2>Mixin 1</h2>
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
<h2>Mixin 2</h2>
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
<h2>Robot Class!</h2>
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
<h2>Usage</h2>
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