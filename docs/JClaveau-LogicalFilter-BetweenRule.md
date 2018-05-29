JClaveau\LogicalFilter\BetweenRule
===============






* Class name: BetweenRule
* Namespace: JClaveau\LogicalFilter
* Parent class: JClaveau\LogicalFilter\Rule





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

    mixed JClaveau\LogicalFilter\BetweenRule::__construct($maximum, $minimum)





* Visibility: **public**


#### Arguments
* $maximum **mixed**
* $minimum **mixed**



### combineWith

    \JClaveau\LogicalFilter\BetweenRule JClaveau\LogicalFilter\BetweenRule::combineWith(\JClaveau\LogicalFilter\Rule $other_rule)





* Visibility: **public**


#### Arguments
* $other_rule **JClaveau\LogicalFilter\Rule**



### getMinimum

    mixed JClaveau\LogicalFilter\BetweenRule::getMinimum()





* Visibility: **public**




### getMaximum

    mixed JClaveau\LogicalFilter\BetweenRule::getMaximum()





* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\BetweenRule::hasSolution()

Checks if the range is valid.



* Visibility: **public**



