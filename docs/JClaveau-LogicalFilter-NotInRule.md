JClaveau\LogicalFilter\NotInRule
===============

This class represents a rule that expect a value to belong to a list of others.




* Class name: NotInRule
* Namespace: JClaveau\LogicalFilter
* Parent class: JClaveau\LogicalFilter\Rule





Properties
----------


### $possibilities

    protected array $possibilities

This property should never be null.



* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\NotInRule::__construct(array $possibilities)





* Visibility: **public**


#### Arguments
* $possibilities **array**



### combineWith

    \JClaveau\LogicalFilter\NotInRule JClaveau\LogicalFilter\NotInRule::combineWith(\JClaveau\LogicalFilter\Rule $other_rule)





* Visibility: **public**


#### Arguments
* $other_rule **JClaveau\LogicalFilter\Rule**



### getPossibilities

    array JClaveau\LogicalFilter\NotInRule::getPossibilities()





* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\NotInRule::hasSolution()

NotIn rule will always have a solution.



* Visibility: **public**



