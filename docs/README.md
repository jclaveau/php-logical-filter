# PHP Logical Filter

## Table of Contents

* [AboveOrEqualRule](#aboveorequalrule)
    * [rootifyDisjunctions](#rootifydisjunctions)
    * [toArray](#toarray)
    * [removeInvalidBranches](#removeinvalidbranches)
    * [hasSolution](#hassolution)
    * [__construct](#__construct)
    * [getMinimum](#getminimum)
    * [getField](#getfield)
    * [getValues](#getvalues)
* [AboveRule](#aboverule)
    * [getField](#getfield-1)
    * [setField](#setfield)
    * [renameField](#renamefield)
    * [toArray](#toarray-1)
    * [findSymbolicOperator](#findsymbolicoperator)
    * [findEnglishOperator](#findenglishoperator)
    * [generateSimpleRule](#generatesimplerule)
    * [getRuleClass](#getruleclass)
    * [copy](#copy)
    * [dump](#dump)
    * [jsonSerialize](#jsonserialize)
    * [__toString](#__tostring)
    * [getInstanceId](#getinstanceid)
    * [getSemanticId](#getsemanticid)
    * [__construct](#__construct-1)
    * [getMinimum](#getminimum-1)
    * [getValues](#getvalues-1)
    * [hasSolution](#hassolution-1)
* [AndRule](#andrule)
    * [rootifyDisjunctions](#rootifydisjunctions-1)
    * [toArray](#toarray-2)
    * [removeInvalidBranches](#removeinvalidbranches-1)
    * [hasSolution](#hassolution-2)
* [BelowOrEqualRule](#beloworequalrule)
    * [rootifyDisjunctions](#rootifydisjunctions-2)
    * [toArray](#toarray-3)
    * [removeInvalidBranches](#removeinvalidbranches-2)
    * [hasSolution](#hassolution-3)
    * [__construct](#__construct-2)
    * [getMaximum](#getmaximum)
    * [getField](#getfield-2)
    * [getValues](#getvalues-2)
* [BelowRule](#belowrule)
    * [getField](#getfield-3)
    * [setField](#setfield-1)
    * [renameField](#renamefield-1)
    * [toArray](#toarray-4)
    * [findSymbolicOperator](#findsymbolicoperator-1)
    * [findEnglishOperator](#findenglishoperator-1)
    * [generateSimpleRule](#generatesimplerule-1)
    * [getRuleClass](#getruleclass-1)
    * [copy](#copy-1)
    * [dump](#dump-1)
    * [jsonSerialize](#jsonserialize-1)
    * [__toString](#__tostring-1)
    * [getInstanceId](#getinstanceid-1)
    * [getSemanticId](#getsemanticid-1)
    * [__construct](#__construct-3)
    * [hasSolution](#hassolution-4)
    * [getMaximum](#getmaximum-1)
    * [getValues](#getvalues-3)
* [BetweenOrEqualBothRule](#betweenorequalbothrule)
    * [__construct](#__construct-4)
    * [getMinimum](#getminimum-2)
    * [getMaximum](#getmaximum-2)
    * [getValues](#getvalues-4)
    * [getField](#getfield-4)
    * [hasSolution](#hassolution-5)
    * [toArray](#toarray-5)
    * [rootifyDisjunctions](#rootifydisjunctions-3)
    * [removeInvalidBranches](#removeinvalidbranches-3)
* [BetweenOrEqualLowerRule](#betweenorequallowerrule)
    * [__construct](#__construct-5)
    * [getMinimum](#getminimum-3)
    * [getMaximum](#getmaximum-3)
    * [getValues](#getvalues-5)
    * [getField](#getfield-5)
    * [hasSolution](#hassolution-6)
    * [toArray](#toarray-6)
    * [rootifyDisjunctions](#rootifydisjunctions-4)
    * [removeInvalidBranches](#removeinvalidbranches-4)
* [BetweenOrEqualUpperRule](#betweenorequalupperrule)
    * [__construct](#__construct-6)
    * [getMinimum](#getminimum-4)
    * [getMaximum](#getmaximum-4)
    * [getValues](#getvalues-6)
    * [getField](#getfield-6)
    * [hasSolution](#hassolution-7)
    * [toArray](#toarray-7)
    * [rootifyDisjunctions](#rootifydisjunctions-5)
    * [removeInvalidBranches](#removeinvalidbranches-5)
* [BetweenRule](#betweenrule)
    * [rootifyDisjunctions](#rootifydisjunctions-6)
    * [toArray](#toarray-8)
    * [removeInvalidBranches](#removeinvalidbranches-6)
    * [hasSolution](#hassolution-8)
    * [__construct](#__construct-7)
    * [getMinimum](#getminimum-5)
    * [getMaximum](#getmaximum-5)
    * [getValues](#getvalues-7)
    * [getField](#getfield-7)
* [CustomizableFilterer](#customizablefilterer)
    * [setCustomActions](#setcustomactions)
    * [onRowMatches](#onrowmatches)
    * [onRowMismatches](#onrowmismatches)
    * [getChildren](#getchildren)
    * [setChildren](#setchildren)
    * [apply](#apply)
    * [__construct](#__construct-8)
    * [validateRule](#validaterule)
* [CustomizableMinimalConverter](#customizableminimalconverter)
    * [convert](#convert)
    * [__construct](#__construct-9)
    * [onOpenOr](#onopenor)
    * [onCloseOr](#oncloseor)
    * [onAndPossibility](#onandpossibility)
* [ElasticSearchMinimalConverter](#elasticsearchminimalconverter)
    * [convert](#convert-1)
    * [onOpenOr](#onopenor-1)
    * [onCloseOr](#oncloseor-1)
    * [onAndPossibility](#onandpossibility-1)
* [EqualRule](#equalrule)
    * [getField](#getfield-8)
    * [setField](#setfield-2)
    * [renameField](#renamefield-2)
    * [toArray](#toarray-9)
    * [findSymbolicOperator](#findsymbolicoperator-2)
    * [findEnglishOperator](#findenglishoperator-2)
    * [generateSimpleRule](#generatesimplerule-2)
    * [getRuleClass](#getruleclass-2)
    * [copy](#copy-2)
    * [dump](#dump-2)
    * [jsonSerialize](#jsonserialize-2)
    * [__toString](#__tostring-2)
    * [getInstanceId](#getinstanceid-2)
    * [getSemanticId](#getsemanticid-2)
    * [__construct](#__construct-10)
    * [getValue](#getvalue)
    * [getValues](#getvalues-8)
    * [hasSolution](#hassolution-9)
* [InlineSqlMinimalConverter](#inlinesqlminimalconverter)
    * [convert](#convert-2)
    * [onOpenOr](#onopenor-2)
    * [onCloseOr](#oncloseor-2)
    * [onAndPossibility](#onandpossibility-2)
* [InRule](#inrule)
    * [rootifyDisjunctions](#rootifydisjunctions-7)
    * [toArray](#toarray-10)
    * [removeInvalidBranches](#removeinvalidbranches-7)
    * [hasSolution](#hassolution-10)
    * [__construct](#__construct-11)
    * [getField](#getfield-9)
    * [renameFields](#renamefields)
    * [getPossibilities](#getpossibilities)
    * [addPossibilities](#addpossibilities)
    * [setPossibilities](#setpossibilities)
    * [getValues](#getvalues-9)
    * [isSimplificationAllowed](#issimplificationallowed)
* [LogicalFilter](#logicalfilter)
    * [__construct](#__construct-12)
    * [and_](#and_)
    * [or_](#or_)
    * [getRules](#getrules)
    * [simplify](#simplify)
    * [hasSolution](#hassolution-11)
    * [toArray](#toarray-11)
    * [jsonSerialize](#jsonserialize-3)
    * [flushRules](#flushrules)
    * [renameFields](#renamefields-1)
    * [removeRules](#removerules)
    * [keepLeafRulesMatching](#keepleafrulesmatching)
    * [listLeafRulesMatching](#listleafrulesmatching)
    * [onEachRule](#oneachrule)
    * [copy](#copy-3)
    * [dump](#dump-3)
    * [applyOn](#applyon)
* [NotEqualRule](#notequalrule)
    * [__construct](#__construct-13)
    * [negateOperand](#negateoperand)
    * [unifyAtomicOperands](#unifyatomicoperands)
    * [toArray](#toarray-12)
    * [rootifyDisjunctions](#rootifydisjunctions-8)
    * [getField](#getfield-10)
    * [getValue](#getvalue-1)
    * [getValues](#getvalues-10)
    * [hasSolution](#hassolution-12)
* [NotInRule](#notinrule)
    * [__construct](#__construct-14)
    * [negateOperand](#negateoperand-1)
    * [unifyAtomicOperands](#unifyatomicoperands-1)
    * [toArray](#toarray-13)
    * [getField](#getfield-11)
    * [getPossibilities](#getpossibilities-1)
    * [setPossibilities](#setpossibilities-1)
    * [getValues](#getvalues-11)
* [NotRule](#notrule)
    * [__construct](#__construct-15)
    * [negateOperand](#negateoperand-2)
    * [unifyAtomicOperands](#unifyatomicoperands-2)
    * [toArray](#toarray-14)
* [OrRule](#orrule)
    * [rootifyDisjunctions](#rootifydisjunctions-9)
    * [toArray](#toarray-15)
    * [removeInvalidBranches](#removeinvalidbranches-8)
    * [hasSolution](#hassolution-13)
* [PhpFilterer](#phpfilterer)
    * [setCustomActions](#setcustomactions-1)
    * [onRowMatches](#onrowmatches-1)
    * [onRowMismatches](#onrowmismatches-1)
    * [getChildren](#getchildren-1)
    * [setChildren](#setchildren-1)
    * [apply](#apply-1)
    * [validateRule](#validaterule-1)
* [RuleFilterer](#rulefilterer)
    * [setCustomActions](#setcustomactions-2)
    * [onRowMatches](#onrowmatches-2)
    * [onRowMismatches](#onrowmismatches-2)
    * [getChildren](#getchildren-2)
    * [setChildren](#setchildren-2)
    * [apply](#apply-2)
    * [validateRule](#validaterule-2)

## AboveOrEqualRule

a >= x

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\AboveOrEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\OrRule


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
AboveOrEqualRule::rootifyDisjunctions(  ): $this
```







---

### toArray



```php
AboveOrEqualRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
AboveOrEqualRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\OrRule
```







---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
AboveOrEqualRule::hasSolution(  ): boolean
```





**Return Value:**

If the rule can have a solution or not



---

### __construct



```php
AboveOrEqualRule::__construct( string $field,  $minimum )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$minimum` | **** |  |




---

### getMinimum



```php
AboveOrEqualRule::getMinimum(  )
```







---

### getField



```php
AboveOrEqualRule::getField(  )
```







---

### getValues



```php
AboveOrEqualRule::getValues(  ): array
```







---

## AboveRule

a > x



* Full name: \JClaveau\LogicalFilter\Rule\AboveRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractAtomicRule


### getField



```php
AboveRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
AboveRule::setField(  $field ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |


**Return Value:**

$field



---

### renameField



```php
AboveRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### toArray



```php
AboveRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### findSymbolicOperator



```php
AboveRule::findSymbolicOperator(  $english_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **** |  |




---

### findEnglishOperator



```php
AboveRule::findEnglishOperator(  $symbolic_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **** |  |




---

### generateSimpleRule



```php
AboveRule::generateSimpleRule( string $field, string $type,  $values ): $this
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **** |  |




---

### getRuleClass



```php
AboveRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### copy

Clones the rule with a chained syntax.

```php
AboveRule::copy(  ): \JClaveau\LogicalFilter\Rule\Rule
```





**Return Value:**

A copy of the current instance.



---

### dump

var_export() the rule with a chained syntax.

```php
AboveRule::dump(  $exit = false,  $debug = false,  $callstack_depth = 2 )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **** |  |
| `$debug` | **** |  |
| `$callstack_depth` | **** |  |




---

### jsonSerialize

For implementing JsonSerializable interface.

```php
AboveRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
AboveRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
AboveRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
AboveRule::getSemanticId(  ): string
```







---

### __construct



```php
AboveRule::__construct( string $field,  $minimum )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$minimum` | **** |  |




---

### getMinimum



```php
AboveRule::getMinimum(  )
```







---

### getValues



```php
AboveRule::getValues(  ): array
```







---

### hasSolution

Checks if the rule do not expect the value to be above infinity.

```php
AboveRule::hasSolution(  ): boolean
```







---

## AndRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\AndRule
* Parent class: 

**See Also:**

* https://en.wikipedia.org/wiki/Logical_conjunction 

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
AndRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### toArray



```php
AndRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
AndRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\AndRule
```





**Return Value:**

$this



---

### hasSolution

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2

```php
AndRule::hasSolution(  ): boolean
```





**Return Value:**

If the AndRule can have a solution or not



---

## BelowOrEqualRule

a <= x

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\OrRule


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BelowOrEqualRule::rootifyDisjunctions(  ): $this
```







---

### toArray



```php
BelowOrEqualRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
BelowOrEqualRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\OrRule
```







---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
BelowOrEqualRule::hasSolution(  ): boolean
```





**Return Value:**

If the rule can have a solution or not



---

### __construct



```php
BelowOrEqualRule::__construct( string $field,  $maximum )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$maximum` | **** |  |




---

### getMaximum



```php
BelowOrEqualRule::getMaximum(  )
```







---

### getField



```php
BelowOrEqualRule::getField(  )
```







---

### getValues



```php
BelowOrEqualRule::getValues(  ): array
```







---

## BelowRule

a < x



* Full name: \JClaveau\LogicalFilter\Rule\BelowRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractAtomicRule


### getField



```php
BelowRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
BelowRule::setField(  $field ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |


**Return Value:**

$field



---

### renameField



```php
BelowRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### toArray



```php
BelowRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### findSymbolicOperator



```php
BelowRule::findSymbolicOperator(  $english_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **** |  |




---

### findEnglishOperator



```php
BelowRule::findEnglishOperator(  $symbolic_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **** |  |




---

### generateSimpleRule



```php
BelowRule::generateSimpleRule( string $field, string $type,  $values ): $this
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **** |  |




---

### getRuleClass



```php
BelowRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### copy

Clones the rule with a chained syntax.

```php
BelowRule::copy(  ): \JClaveau\LogicalFilter\Rule\Rule
```





**Return Value:**

A copy of the current instance.



---

### dump

var_export() the rule with a chained syntax.

```php
BelowRule::dump(  $exit = false,  $debug = false,  $callstack_depth = 2 )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **** |  |
| `$debug` | **** |  |
| `$callstack_depth` | **** |  |




---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BelowRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BelowRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BelowRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BelowRule::getSemanticId(  ): string
```







---

### __construct



```php
BelowRule::__construct( string $field,  $maximum )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$maximum` | **** |  |




---

### hasSolution

Checks if the rule do not expect the value to be above infinity.

```php
BelowRule::hasSolution(  ): boolean
```







---

### getMaximum



```php
BelowRule::getMaximum(  )
```







---

### getValues



```php
BelowRule::getValues(  )
```







---

## BetweenOrEqualBothRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\BetweenOrEqualBothRule
* Parent class: \JClaveau\LogicalFilter\Rule\BetweenRule


### __construct



```php
BetweenOrEqualBothRule::__construct(  $field, array $limits )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$limits` | **array** |  |




---

### getMinimum



```php
BetweenOrEqualBothRule::getMinimum(  ): mixed
```







---

### getMaximum



```php
BetweenOrEqualBothRule::getMaximum(  ): mixed
```







---

### getValues



```php
BetweenOrEqualBothRule::getValues(  ): array
```







---

### getField



```php
BetweenOrEqualBothRule::getField(  )
```







---

### hasSolution

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2

```php
BetweenOrEqualBothRule::hasSolution(  ): boolean
```





**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualBothRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualBothRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualBothRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\AndRule
```





**Return Value:**

$this



---

## BetweenOrEqualLowerRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\BetweenOrEqualLowerRule
* Parent class: \JClaveau\LogicalFilter\Rule\BetweenRule


### __construct



```php
BetweenOrEqualLowerRule::__construct(  $field, array $limits )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$limits` | **array** |  |




---

### getMinimum



```php
BetweenOrEqualLowerRule::getMinimum(  ): mixed
```







---

### getMaximum



```php
BetweenOrEqualLowerRule::getMaximum(  ): mixed
```







---

### getValues



```php
BetweenOrEqualLowerRule::getValues(  ): array
```







---

### getField



```php
BetweenOrEqualLowerRule::getField(  )
```







---

### hasSolution

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2

```php
BetweenOrEqualLowerRule::hasSolution(  ): boolean
```





**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualLowerRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualLowerRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualLowerRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\AndRule
```





**Return Value:**

$this



---

## BetweenOrEqualUpperRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\BetweenOrEqualUpperRule
* Parent class: \JClaveau\LogicalFilter\Rule\BetweenRule


### __construct



```php
BetweenOrEqualUpperRule::__construct(  $field, array $limits )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$limits` | **array** |  |




---

### getMinimum



```php
BetweenOrEqualUpperRule::getMinimum(  ): mixed
```







---

### getMaximum



```php
BetweenOrEqualUpperRule::getMaximum(  ): mixed
```







---

### getValues



```php
BetweenOrEqualUpperRule::getValues(  ): array
```







---

### getField



```php
BetweenOrEqualUpperRule::getField(  )
```







---

### hasSolution

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2

```php
BetweenOrEqualUpperRule::hasSolution(  ): boolean
```





**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualUpperRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualUpperRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualUpperRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\AndRule
```





**Return Value:**

$this



---

## BetweenRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\BetweenRule
* Parent class: \JClaveau\LogicalFilter\Rule\AndRule


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### toArray



```php
BetweenRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\AndRule
```





**Return Value:**

$this



---

### hasSolution

Checks if the range is valid.

```php
BetweenRule::hasSolution(  ): boolean
```







---

### __construct



```php
BetweenRule::__construct(  $field, array $limits )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$limits` | **array** |  |




---

### getMinimum



```php
BetweenRule::getMinimum(  ): mixed
```







---

### getMaximum



```php
BetweenRule::getMaximum(  ): mixed
```







---

### getValues



```php
BetweenRule::getValues(  ): array
```







---

### getField



```php
BetweenRule::getField(  )
```







---

## CustomizableFilterer

This filterer provides the tools and API to apply a LogicalFilter once it has
been simplified.



* Full name: \JClaveau\LogicalFilter\Filterer\CustomizableFilterer
* Parent class: \JClaveau\LogicalFilter\Filterer\Filterer


### setCustomActions



```php
CustomizableFilterer::setCustomActions( array $custom_actions )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$custom_actions` | **array** |  |




---

### onRowMatches



```php
CustomizableFilterer::onRowMatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### onRowMismatches



```php
CustomizableFilterer::onRowMismatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### getChildren



```php
CustomizableFilterer::getChildren(  $row ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |




---

### setChildren



```php
CustomizableFilterer::setChildren(  &$row,  $filtered_children )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$filtered_children` | **** |  |




---

### apply



```php
CustomizableFilterer::apply( \JClaveau\LogicalFilter\LogicalFilter $filter, \JClaveau\LogicalFilter\Filterer\Iterable|object $tree_to_filter, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$tree_to_filter` | **\JClaveau\LogicalFilter\Filterer\Iterable&#124;object** |  |
| `$options` | **array** |  |




---

### __construct



```php
CustomizableFilterer::__construct( callable $rule_validator )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_validator` | **callable** |  |




---

### validateRule



```php
CustomizableFilterer::validateRule(  $field,  $operator,  $value,  $row,  $depth,  $all_operands,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$row` | **** |  |
| `$depth` | **** |  |
| `$all_operands` | **** |  |
| `$options` | **** |  |




---

## CustomizableMinimalConverter

This class implements a converter using callbacks for every pseudo-event
related to the rules parsing.



* Full name: \JClaveau\LogicalFilter\Converter\CustomizableMinimalConverter
* Parent class: \JClaveau\LogicalFilter\Converter\MinimalConverter


### convert



```php
CustomizableMinimalConverter::convert( \JClaveau\LogicalFilter\LogicalFilter $filter )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |




---

### __construct



```php
CustomizableMinimalConverter::__construct( callable $onOpenOr, callable $onAndPossibility, callable $onCloseOr )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onOpenOr` | **callable** |  |
| `$onAndPossibility` | **callable** |  |
| `$onCloseOr` | **callable** |  |




---

### onOpenOr



```php
CustomizableMinimalConverter::onOpenOr(  )
```







---

### onCloseOr



```php
CustomizableMinimalConverter::onCloseOr(  )
```







---

### onAndPossibility

Pseudo-event called while for each And operand of the root Or.

```php
CustomizableMinimalConverter::onAndPossibility(  $field,  $operator,  $operand, array $allOperandsByField )
```

These operands must be only atomic Rules.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$operand` | **** |  |
| `$allOperandsByField` | **array** |  |




---

## ElasticSearchMinimalConverter

This class implements a converter for ElasticSearch.



* Full name: \JClaveau\LogicalFilter\Converter\ElasticSearchMinimalConverter
* Parent class: \JClaveau\LogicalFilter\Converter\MinimalConverter


### convert



```php
ElasticSearchMinimalConverter::convert( \JClaveau\LogicalFilter\LogicalFilter $filter )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |




---

### onOpenOr



```php
ElasticSearchMinimalConverter::onOpenOr(  )
```







---

### onCloseOr



```php
ElasticSearchMinimalConverter::onCloseOr(  )
```







---

### onAndPossibility

Pseudo-event called while for each And operand of the root Or.

```php
ElasticSearchMinimalConverter::onAndPossibility(  $field,  $operator,  $operand, array $allOperandsByField )
```

These operands must be only atomic Rules.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$operand` | **** |  |
| `$allOperandsByField` | **array** |  |




---

## EqualRule

a = x



* Full name: \JClaveau\LogicalFilter\Rule\EqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractAtomicRule


### getField



```php
EqualRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
EqualRule::setField(  $field ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |


**Return Value:**

$field



---

### renameField



```php
EqualRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### toArray



```php
EqualRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### findSymbolicOperator



```php
EqualRule::findSymbolicOperator(  $english_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **** |  |




---

### findEnglishOperator



```php
EqualRule::findEnglishOperator(  $symbolic_operator )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **** |  |




---

### generateSimpleRule



```php
EqualRule::generateSimpleRule( string $field, string $type,  $values ): $this
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **** |  |




---

### getRuleClass



```php
EqualRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### copy

Clones the rule with a chained syntax.

```php
EqualRule::copy(  ): \JClaveau\LogicalFilter\Rule\Rule
```





**Return Value:**

A copy of the current instance.



---

### dump

var_export() the rule with a chained syntax.

```php
EqualRule::dump(  $exit = false,  $debug = false,  $callstack_depth = 2 )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **** |  |
| `$debug` | **** |  |
| `$callstack_depth` | **** |  |




---

### jsonSerialize

For implementing JsonSerializable interface.

```php
EqualRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
EqualRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
EqualRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
EqualRule::getSemanticId(  ): string
```







---

### __construct



```php
EqualRule::__construct( string $field, array $value )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$value` | **array** | The value the field can equal to. |




---

### getValue



```php
EqualRule::getValue(  )
```







---

### getValues



```php
EqualRule::getValues(  ): array
```







---

### hasSolution

By default, every atomic rule can have a solution by itself

```php
EqualRule::hasSolution(  ): boolean
```







---

## InlineSqlMinimalConverter

This class implements a converter for MySQL.



* Full name: \JClaveau\LogicalFilter\Converter\InlineSqlMinimalConverter
* Parent class: \JClaveau\LogicalFilter\Converter\MinimalConverter


### convert



```php
InlineSqlMinimalConverter::convert( \JClaveau\LogicalFilter\LogicalFilter $filter )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |




---

### onOpenOr



```php
InlineSqlMinimalConverter::onOpenOr(  )
```







---

### onCloseOr



```php
InlineSqlMinimalConverter::onCloseOr(  )
```







---

### onAndPossibility

Pseudo-event called while for each And operand of the root Or.

```php
InlineSqlMinimalConverter::onAndPossibility(  $field,  $operator,  $rule, array $allOperandsByField )
```

These operands must be only atomic Rules.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$rule` | **** |  |
| `$allOperandsByField` | **array** |  |




---

## InRule

This class represents a rule that expect a value to belong to a list of others.

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\InRule
* Parent class: \JClaveau\LogicalFilter\Rule\OrRule


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
InRule::rootifyDisjunctions(  ): $this
```







---

### toArray



```php
InRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
InRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\OrRule
```







---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
InRule::hasSolution(  ): boolean
```





**Return Value:**

If the rule can have a solution or not



---

### __construct



```php
InRule::__construct( string $field, mixed $possibilities )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$possibilities` | **mixed** | The values the field can belong to. |




---

### getField



```php
InRule::getField(  ): string
```





**Return Value:**

The field



---

### renameFields



```php
InRule::renameFields(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### getPossibilities



```php
InRule::getPossibilities(  ): array
```







---

### addPossibilities



```php
InRule::addPossibilities(  $possibilities ): \JClaveau\LogicalFilter\Rule\InRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$possibilities` | **** |  |


**Return Value:**

$this



---

### setPossibilities



```php
InRule::setPossibilities(  $possibilities ): \JClaveau\LogicalFilter\Rule\InRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$possibilities` | **** |  |


**Return Value:**

$this



---

### getValues



```php
InRule::getValues(  ): array
```







---

### isSimplificationAllowed



```php
InRule::isSimplificationAllowed(  )
```







---

## LogicalFilter

LogicalFilter describes a set of logical rules structured by
conjunctions and disjunctions (AND and OR).

It's able to simplify them in order to find contractories branches
of the tree rule and check if there is at least one set rules having
possibilities.

* Full name: \JClaveau\LogicalFilter\LogicalFilter
* This class implements: \JsonSerializable


### __construct

Creates a filter. You can provide a description of rules as in
addRules() as paramater.

```php
LogicalFilter::__construct( array $rules = array(), \JClaveau\LogicalFilter\Filterer\Filterer $default_filterer = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rules` | **array** |  |
| `$default_filterer` | **\JClaveau\LogicalFilter\Filterer\Filterer** |  |



**See Also:**

* self::addRules 

---

### and_

This method parses different ways to define the rules of a LogicalFilter
and add them as a new And part of the filter.

```php
LogicalFilter::and_(  ): $this
```

+ You can add N already instanciated Rules.
+ You can provide 3 arguments: $field, $operator, $value
+ You can provide a tree of rules:
[
     'or',
     [
         'and',
         ['field_5', 'above', 'a'],
         ['field_5', 'below', 'a'],
     ],
     ['field_6', 'equal', 'b'],
 ]





---

### or_

This method parses different ways to define the rules of a LogicalFilter
and add them as a new Or part of the filter.

```php
LogicalFilter::or_(  ): $this
```

+ You can add N already instanciated Rules.
+ You can provide 3 arguments: $field, $operator, $value
+ You can provide a tree of rules:
[
     'or',
     [
         'and',
         ['field_5', 'above', 'a'],
         ['field_5', 'below', 'a'],
     ],
     ['field_6', 'equal', 'b'],
 ]





---

### getRules

Retrieve all the rules.

```php
LogicalFilter::getRules( boolean $copy = true ): \JClaveau\LogicalFilter\Rule\AbstractRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$copy` | **boolean** | By default copy the rule tree to avoid side effects. |


**Return Value:**

The tree of rules



---

### simplify

Remove any constraint being a duplicate of another one.

```php
LogicalFilter::simplify( array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; |




---

### hasSolution

Checks if there is at least on set of conditions which is not
contradictory.

```php
LogicalFilter::hasSolution(  $save_simplification = true ): boolean
```

Checking if a filter has solutions require to simplify it first.
To let the control on the balance between readability and
performances, the required simplification can be saved or not
depending on the $save_simplification parameter.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$save_simplification` | **** |  |




---

### toArray

Returns an array describing the rule tree of the Filter.

```php
LogicalFilter::toArray(  $debug = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** | Provides a source oriented dump. |


**Return Value:**

A description of the rules.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
LogicalFilter::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### flushRules

Removes all the defined rules.

```php
LogicalFilter::flushRules(  ): $this
```







---

### renameFields



```php
LogicalFilter::renameFields(  $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### removeRules



```php
LogicalFilter::removeRules(  $filter ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **** |  |


**Return Value:**

$this



---

### keepLeafRulesMatching



```php
LogicalFilter::keepLeafRulesMatching(  $filter = array(), array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **** |  |
| `$options` | **array** |  |


**Return Value:**

The rules matching the filter



---

### listLeafRulesMatching



```php
LogicalFilter::listLeafRulesMatching(  $filter = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **** |  |


**Return Value:**

The rules matching the filter



---

### onEachRule



```php
LogicalFilter::onEachRule(  $filter = array(),  $options ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **** |  |
| `$options` | **** |  |


**Return Value:**

The rules matching the filter



---

### copy

Clone the current object and its rules.

```php
LogicalFilter::copy(  ): \JClaveau\LogicalFilter\LogicalFilter
```





**Return Value:**

A copy of the current instance with a copied ruletree



---

### dump



```php
LogicalFilter::dump(  $exit = false,  $debug = false,  $callstack_depth = 2 )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **** |  |
| `$debug` | **** |  |
| `$callstack_depth` | **** |  |




---

### applyOn

Applies the current instance to a set of data.

```php
LogicalFilter::applyOn( mixed $data_to_filter,  $action_on_matches = null, \JClaveau\LogicalFilter\Filterer\Filterer|callable|null $filterer = null ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data_to_filter` | **mixed** |  |
| `$action_on_matches` | **** |  |
| `$filterer` | **\JClaveau\LogicalFilter\Filterer\Filterer&#124;callable&#124;null** |  |


**Return Value:**

The filtered data



---

## NotEqualRule

a != x



* Full name: \JClaveau\LogicalFilter\Rule\NotEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\NotRule


### __construct



```php
NotEqualRule::__construct( string $field, array $value )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$value` | **array** | The value the field can equal to. |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotEqualRule::negateOperand(  $remove_generated_negations = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$remove_generated_negations` | **** |  |




---

### unifyAtomicOperands

Not rules can only have one operand.

```php
NotEqualRule::unifyAtomicOperands(  $unifyDifferentOperands = true ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$unifyDifferentOperands` | **** |  |




---

### toArray



```php
NotEqualRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
NotEqualRule::rootifyDisjunctions(  ): \JClaveau\LogicalFilter\Rule\OrRule
```





**Return Value:**

copied operands with one OR at its root



---

### getField



```php
NotEqualRule::getField(  )
```







---

### getValue



```php
NotEqualRule::getValue(  )
```







---

### getValues



```php
NotEqualRule::getValues(  )
```







---

### hasSolution

By default, every atomic rule can have a solution by itself

```php
NotEqualRule::hasSolution(  ): boolean
```







---

## NotInRule

a ! in x



* Full name: \JClaveau\LogicalFilter\Rule\NotInRule
* Parent class: \JClaveau\LogicalFilter\Rule\NotRule


### __construct



```php
NotInRule::__construct( string $field, array $possibilities )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$possibilities` | **array** |  |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotInRule::negateOperand(  $remove_generated_negations = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$remove_generated_negations` | **** |  |




---

### unifyAtomicOperands

Not rules can only have one operand.

```php
NotInRule::unifyAtomicOperands(  $unifyDifferentOperands = true ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$unifyDifferentOperands` | **** |  |




---

### toArray



```php
NotInRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### getField



```php
NotInRule::getField(  )
```







---

### getPossibilities



```php
NotInRule::getPossibilities(  ): array
```







---

### setPossibilities



```php
NotInRule::setPossibilities( array $possibilities ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$possibilities` | **array** |  |




---

### getValues



```php
NotInRule::getValues(  )
```







---

## NotRule

Logical negation:



* Full name: \JClaveau\LogicalFilter\Rule\NotRule
* Parent class: 

**See Also:**

* https://en.wikipedia.org/wiki/Negation 

### __construct



```php
NotRule::__construct( \JClaveau\LogicalFilter\Rule\AbstractRule $operand = null )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotRule::negateOperand(  $remove_generated_negations = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$remove_generated_negations` | **** |  |




---

### unifyAtomicOperands

Not rules can only have one operand.

```php
NotRule::unifyAtomicOperands(  $unifyDifferentOperands = true ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$unifyDifferentOperands` | **** |  |




---

### toArray



```php
NotRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

## OrRule

Logical inclusive disjunction

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\OrRule
* Parent class: 


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
OrRule::rootifyDisjunctions(  ): $this
```







---

### toArray



```php
OrRule::toArray(  $debug = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$debug` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
OrRule::removeInvalidBranches(  ): \JClaveau\LogicalFilter\Rule\OrRule
```







---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
OrRule::hasSolution(  ): boolean
```





**Return Value:**

If the rule can have a solution or not



---

## PhpFilterer

This filterer provides the tools and API to apply a LogicalFilter once it has
been simplified.



* Full name: \JClaveau\LogicalFilter\Filterer\PhpFilterer
* Parent class: \JClaveau\LogicalFilter\Filterer\Filterer


### setCustomActions



```php
PhpFilterer::setCustomActions( array $custom_actions )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$custom_actions` | **array** |  |




---

### onRowMatches



```php
PhpFilterer::onRowMatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### onRowMismatches



```php
PhpFilterer::onRowMismatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### getChildren



```php
PhpFilterer::getChildren(  $row ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |




---

### setChildren



```php
PhpFilterer::setChildren(  &$row,  $filtered_children )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$filtered_children` | **** |  |




---

### apply



```php
PhpFilterer::apply( \JClaveau\LogicalFilter\LogicalFilter $filter, \JClaveau\LogicalFilter\Filterer\Iterable|object $tree_to_filter, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$tree_to_filter` | **\JClaveau\LogicalFilter\Filterer\Iterable&#124;object** |  |
| `$options` | **array** |  |




---

### validateRule



```php
PhpFilterer::validateRule(  $field,  $operator,  $value,  $row,  $depth,  $all_operands,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$row` | **** |  |
| `$depth` | **** |  |
| `$all_operands` | **** |  |
| `$options` | **** |  |




---

## RuleFilterer

This filterer is intended to validate Rules.

Manipulating the rules of a logical filter is easier with another one.
This filterer is used for the functions of the exposed api like
removeRules(), manipulateRules()

* Full name: \JClaveau\LogicalFilter\Filterer\RuleFilterer
* Parent class: \JClaveau\LogicalFilter\Filterer\Filterer


### setCustomActions



```php
RuleFilterer::setCustomActions( array $custom_actions )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$custom_actions` | **array** |  |




---

### onRowMatches



```php
RuleFilterer::onRowMatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### onRowMismatches



```php
RuleFilterer::onRowMismatches(  &$row,  $key,  &$rows,  $matching_case,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$key` | **** |  |
| `$rows` | **** |  |
| `$matching_case` | **** |  |
| `$options` | **** |  |




---

### getChildren



```php
RuleFilterer::getChildren(  $row ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |




---

### setChildren



```php
RuleFilterer::setChildren(  &$row,  $filtered_children )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **** |  |
| `$filtered_children` | **** |  |




---

### apply



```php
RuleFilterer::apply( \JClaveau\LogicalFilter\LogicalFilter $filter, array|\JClaveau\LogicalFilter\Rule\AbstractRule $ruleTree_to_filter, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$ruleTree_to_filter` | **array&#124;\JClaveau\LogicalFilter\Rule\AbstractRule** |  |
| `$options` | **array** | leafs_only &#124; debug |




---

### validateRule



```php
RuleFilterer::validateRule(  $field,  $operator,  $value,  $rule,  $depth,  $all_operands,  $options ): true
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$rule` | **** |  |
| `$depth` | **** |  |
| `$all_operands` | **** |  |
| `$options` | **** |  |


**Return Value:**

| false | null



---



--------
> This document was automatically generated from source code comments on 2018-07-13 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
