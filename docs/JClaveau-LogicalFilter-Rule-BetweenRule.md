JClaveau\LogicalFilter\Rule\BetweenRule
===============






* Class name: BetweenRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: JClaveau\LogicalFilter\Rule\Rule





Properties
----------


### $maximum

    protected mixed $maximum





* Visibility: **protected**


### $minimum

    protected mixed $minimum





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\BetweenRule::__construct($maximum, $minimum)





* Visibility: **public**


#### Arguments
* $maximum **mixed**
* $minimum **mixed**



### combineWith

    \JClaveau\LogicalFilter\Rule\BetweenRule JClaveau\LogicalFilter\Rule\BetweenRule::combineWith(\JClaveau\LogicalFilter\Rule\Rule $other_rule)





* Visibility: **public**


#### Arguments
* $other_rule **JClaveau\LogicalFilter\Rule\Rule**



### getMinimum

    mixed JClaveau\LogicalFilter\Rule\BetweenRule::getMinimum()





* Visibility: **public**




### getMaximum

    mixed JClaveau\LogicalFilter\Rule\BetweenRule::getMaximum()





* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\Rule\BetweenRule::hasSolution()

Checks if the range is valid.



* Visibility: **public**



