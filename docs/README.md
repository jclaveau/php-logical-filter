# PHP Logical Filter

## Table of Contents

* [AboveOrEqualRule](#aboveorequalrule)
    * [getField](#getfield)
    * [setField](#setfield)
    * [renameField](#renamefield)
    * [removeSameOperationOperands](#removesameoperationoperands)
    * [rootifyDisjunctions](#rootifydisjunctions)
    * [toArray](#toarray)
    * [toString](#tostring)
    * [removeInvalidBranches](#removeinvalidbranches)
    * [hasSolution](#hassolution)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation)
    * [__construct](#__construct)
    * [isSimplified](#issimplified)
    * [addOperand](#addoperand)
    * [getOperands](#getoperands)
    * [setOperands](#setoperands)
    * [renameFields](#renamefields)
    * [moveSimplificationStepForward](#movesimplificationstepforward)
    * [getSimplificationStep](#getsimplificationstep)
    * [simplicationStepReached](#simplicationstepreached)
    * [removeNegations](#removenegations)
    * [cleanOperations](#cleanoperations)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands)
    * [unifyAtomicOperands](#unifyatomicoperands)
    * [simplify](#simplify)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator)
    * [copy](#copy)
    * [__clone](#__clone)
    * [isNormalizationAllowed](#isnormalizationallowed)
    * [findSymbolicOperator](#findsymbolicoperator)
    * [findEnglishOperator](#findenglishoperator)
    * [generateSimpleRule](#generatesimplerule)
    * [getRuleClass](#getruleclass)
    * [dump](#dump)
    * [flushCache](#flushcache)
    * [flushStaticCache](#flushstaticcache)
    * [jsonSerialize](#jsonserialize)
    * [__toString](#__tostring)
    * [getInstanceId](#getinstanceid)
    * [getSemanticId](#getsemanticid)
    * [addMinimalCase](#addminimalcase)
    * [setOptions](#setoptions)
    * [getOption](#getoption)
    * [getOptions](#getoptions)
    * [getMinimum](#getminimum)
    * [setMinimum](#setminimum)
    * [getValues](#getvalues)
* [AboveRule](#aboverule)
    * [toArray](#toarray-1)
    * [toString](#tostring-1)
    * [getField](#getfield-1)
    * [setField](#setfield-1)
    * [renameField](#renamefield-1)
    * [findSymbolicOperator](#findsymbolicoperator-1)
    * [findEnglishOperator](#findenglishoperator-1)
    * [generateSimpleRule](#generatesimplerule-1)
    * [getRuleClass](#getruleclass-1)
    * [copy](#copy-1)
    * [dump](#dump-1)
    * [flushCache](#flushcache-1)
    * [flushStaticCache](#flushstaticcache-1)
    * [jsonSerialize](#jsonserialize-1)
    * [__toString](#__tostring-1)
    * [getInstanceId](#getinstanceid-1)
    * [getSemanticId](#getsemanticid-1)
    * [addMinimalCase](#addminimalcase-1)
    * [setOptions](#setoptions-1)
    * [getOption](#getoption-1)
    * [getOptions](#getoptions-1)
    * [__construct](#__construct-1)
    * [getMinimum](#getminimum-1)
    * [getLowerLimit](#getlowerlimit)
    * [getValues](#getvalues-1)
    * [hasSolution](#hassolution-1)
* [AndRule](#andrule)
    * [__construct](#__construct-2)
    * [isSimplified](#issimplified-1)
    * [addOperand](#addoperand-1)
    * [getOperands](#getoperands-1)
    * [setOperands](#setoperands-1)
    * [renameFields](#renamefields-1)
    * [moveSimplificationStepForward](#movesimplificationstepforward-1)
    * [getSimplificationStep](#getsimplificationstep-1)
    * [simplicationStepReached](#simplicationstepreached-1)
    * [removeNegations](#removenegations-1)
    * [cleanOperations](#cleanoperations-1)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-1)
    * [unifyAtomicOperands](#unifyatomicoperands-1)
    * [simplify](#simplify-1)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-1)
    * [copy](#copy-2)
    * [__clone](#__clone-1)
    * [isNormalizationAllowed](#isnormalizationallowed-1)
    * [findSymbolicOperator](#findsymbolicoperator-2)
    * [findEnglishOperator](#findenglishoperator-2)
    * [generateSimpleRule](#generatesimplerule-2)
    * [getRuleClass](#getruleclass-2)
    * [dump](#dump-2)
    * [flushCache](#flushcache-2)
    * [flushStaticCache](#flushstaticcache-2)
    * [jsonSerialize](#jsonserialize-2)
    * [__toString](#__tostring-2)
    * [toString](#tostring-2)
    * [toArray](#toarray-2)
    * [getInstanceId](#getinstanceid-2)
    * [getSemanticId](#getsemanticid-2)
    * [addMinimalCase](#addminimalcase-2)
    * [setOptions](#setoptions-2)
    * [getOption](#getoption-2)
    * [getOptions](#getoptions-2)
    * [rootifyDisjunctions](#rootifydisjunctions-1)
    * [removeSameOperationOperands](#removesameoperationoperands-1)
    * [removeInvalidBranches](#removeinvalidbranches-1)
    * [hasSolution](#hassolution-2)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-1)
* [BelowOrEqualRule](#beloworequalrule)
    * [getField](#getfield-2)
    * [setField](#setfield-2)
    * [renameField](#renamefield-2)
    * [removeSameOperationOperands](#removesameoperationoperands-2)
    * [rootifyDisjunctions](#rootifydisjunctions-2)
    * [toArray](#toarray-3)
    * [toString](#tostring-3)
    * [removeInvalidBranches](#removeinvalidbranches-2)
    * [hasSolution](#hassolution-3)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-2)
    * [__construct](#__construct-3)
    * [isSimplified](#issimplified-2)
    * [addOperand](#addoperand-2)
    * [getOperands](#getoperands-2)
    * [setOperands](#setoperands-2)
    * [renameFields](#renamefields-2)
    * [moveSimplificationStepForward](#movesimplificationstepforward-2)
    * [getSimplificationStep](#getsimplificationstep-2)
    * [simplicationStepReached](#simplicationstepreached-2)
    * [removeNegations](#removenegations-2)
    * [cleanOperations](#cleanoperations-2)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-2)
    * [unifyAtomicOperands](#unifyatomicoperands-2)
    * [simplify](#simplify-2)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-2)
    * [copy](#copy-3)
    * [__clone](#__clone-2)
    * [isNormalizationAllowed](#isnormalizationallowed-2)
    * [findSymbolicOperator](#findsymbolicoperator-3)
    * [findEnglishOperator](#findenglishoperator-3)
    * [generateSimpleRule](#generatesimplerule-3)
    * [getRuleClass](#getruleclass-3)
    * [dump](#dump-3)
    * [flushCache](#flushcache-3)
    * [flushStaticCache](#flushstaticcache-3)
    * [jsonSerialize](#jsonserialize-3)
    * [__toString](#__tostring-3)
    * [getInstanceId](#getinstanceid-3)
    * [getSemanticId](#getsemanticid-3)
    * [addMinimalCase](#addminimalcase-3)
    * [setOptions](#setoptions-3)
    * [getOption](#getoption-3)
    * [getOptions](#getoptions-3)
    * [getMaximum](#getmaximum)
    * [setMaximum](#setmaximum)
    * [getValues](#getvalues-2)
* [BelowRule](#belowrule)
    * [toArray](#toarray-4)
    * [toString](#tostring-4)
    * [getField](#getfield-3)
    * [setField](#setfield-3)
    * [renameField](#renamefield-3)
    * [findSymbolicOperator](#findsymbolicoperator-4)
    * [findEnglishOperator](#findenglishoperator-4)
    * [generateSimpleRule](#generatesimplerule-4)
    * [getRuleClass](#getruleclass-4)
    * [copy](#copy-4)
    * [dump](#dump-4)
    * [flushCache](#flushcache-4)
    * [flushStaticCache](#flushstaticcache-4)
    * [jsonSerialize](#jsonserialize-4)
    * [__toString](#__tostring-4)
    * [getInstanceId](#getinstanceid-4)
    * [getSemanticId](#getsemanticid-4)
    * [addMinimalCase](#addminimalcase-4)
    * [setOptions](#setoptions-4)
    * [getOption](#getoption-4)
    * [getOptions](#getoptions-4)
    * [__construct](#__construct-4)
    * [hasSolution](#hassolution-4)
    * [getMaximum](#getmaximum-1)
    * [getUpperLimit](#getupperlimit)
    * [getValues](#getvalues-3)
* [BetweenOrEqualBothRule](#betweenorequalbothrule)
    * [__construct](#__construct-5)
    * [getMinimum](#getminimum-2)
    * [getMaximum](#getmaximum-2)
    * [getValues](#getvalues-4)
    * [getField](#getfield-4)
    * [hasSolution](#hassolution-5)
    * [toArray](#toarray-5)
    * [toString](#tostring-5)
    * [rootifyDisjunctions](#rootifydisjunctions-3)
    * [removeSameOperationOperands](#removesameoperationoperands-3)
    * [removeInvalidBranches](#removeinvalidbranches-3)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-3)
    * [isSimplified](#issimplified-3)
    * [addOperand](#addoperand-3)
    * [getOperands](#getoperands-3)
    * [setOperands](#setoperands-3)
    * [renameFields](#renamefields-3)
    * [moveSimplificationStepForward](#movesimplificationstepforward-3)
    * [getSimplificationStep](#getsimplificationstep-3)
    * [simplicationStepReached](#simplicationstepreached-3)
    * [removeNegations](#removenegations-3)
    * [cleanOperations](#cleanoperations-3)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-3)
    * [unifyAtomicOperands](#unifyatomicoperands-3)
    * [simplify](#simplify-3)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-3)
    * [copy](#copy-5)
    * [__clone](#__clone-3)
    * [isNormalizationAllowed](#isnormalizationallowed-3)
    * [findSymbolicOperator](#findsymbolicoperator-5)
    * [findEnglishOperator](#findenglishoperator-5)
    * [generateSimpleRule](#generatesimplerule-5)
    * [getRuleClass](#getruleclass-5)
    * [dump](#dump-5)
    * [flushCache](#flushcache-5)
    * [flushStaticCache](#flushstaticcache-5)
    * [jsonSerialize](#jsonserialize-5)
    * [__toString](#__tostring-5)
    * [getInstanceId](#getinstanceid-5)
    * [getSemanticId](#getsemanticid-5)
    * [addMinimalCase](#addminimalcase-5)
    * [setOptions](#setoptions-5)
    * [getOption](#getoption-5)
    * [getOptions](#getoptions-5)
* [BetweenOrEqualLowerRule](#betweenorequallowerrule)
    * [__construct](#__construct-6)
    * [getMinimum](#getminimum-3)
    * [getMaximum](#getmaximum-3)
    * [getValues](#getvalues-5)
    * [getField](#getfield-5)
    * [hasSolution](#hassolution-6)
    * [toArray](#toarray-6)
    * [toString](#tostring-6)
    * [rootifyDisjunctions](#rootifydisjunctions-4)
    * [removeSameOperationOperands](#removesameoperationoperands-4)
    * [removeInvalidBranches](#removeinvalidbranches-4)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-4)
    * [isSimplified](#issimplified-4)
    * [addOperand](#addoperand-4)
    * [getOperands](#getoperands-4)
    * [setOperands](#setoperands-4)
    * [renameFields](#renamefields-4)
    * [moveSimplificationStepForward](#movesimplificationstepforward-4)
    * [getSimplificationStep](#getsimplificationstep-4)
    * [simplicationStepReached](#simplicationstepreached-4)
    * [removeNegations](#removenegations-4)
    * [cleanOperations](#cleanoperations-4)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-4)
    * [unifyAtomicOperands](#unifyatomicoperands-4)
    * [simplify](#simplify-4)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-4)
    * [copy](#copy-6)
    * [__clone](#__clone-4)
    * [isNormalizationAllowed](#isnormalizationallowed-4)
    * [findSymbolicOperator](#findsymbolicoperator-6)
    * [findEnglishOperator](#findenglishoperator-6)
    * [generateSimpleRule](#generatesimplerule-6)
    * [getRuleClass](#getruleclass-6)
    * [dump](#dump-6)
    * [flushCache](#flushcache-6)
    * [flushStaticCache](#flushstaticcache-6)
    * [jsonSerialize](#jsonserialize-6)
    * [__toString](#__tostring-6)
    * [getInstanceId](#getinstanceid-6)
    * [getSemanticId](#getsemanticid-6)
    * [addMinimalCase](#addminimalcase-6)
    * [setOptions](#setoptions-6)
    * [getOption](#getoption-6)
    * [getOptions](#getoptions-6)
* [BetweenOrEqualUpperRule](#betweenorequalupperrule)
    * [__construct](#__construct-7)
    * [getMinimum](#getminimum-4)
    * [getMaximum](#getmaximum-4)
    * [getValues](#getvalues-6)
    * [getField](#getfield-6)
    * [hasSolution](#hassolution-7)
    * [toArray](#toarray-7)
    * [toString](#tostring-7)
    * [rootifyDisjunctions](#rootifydisjunctions-5)
    * [removeSameOperationOperands](#removesameoperationoperands-5)
    * [removeInvalidBranches](#removeinvalidbranches-5)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-5)
    * [isSimplified](#issimplified-5)
    * [addOperand](#addoperand-5)
    * [getOperands](#getoperands-5)
    * [setOperands](#setoperands-5)
    * [renameFields](#renamefields-5)
    * [moveSimplificationStepForward](#movesimplificationstepforward-5)
    * [getSimplificationStep](#getsimplificationstep-5)
    * [simplicationStepReached](#simplicationstepreached-5)
    * [removeNegations](#removenegations-5)
    * [cleanOperations](#cleanoperations-5)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-5)
    * [unifyAtomicOperands](#unifyatomicoperands-5)
    * [simplify](#simplify-5)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-5)
    * [copy](#copy-7)
    * [__clone](#__clone-5)
    * [isNormalizationAllowed](#isnormalizationallowed-5)
    * [findSymbolicOperator](#findsymbolicoperator-7)
    * [findEnglishOperator](#findenglishoperator-7)
    * [generateSimpleRule](#generatesimplerule-7)
    * [getRuleClass](#getruleclass-7)
    * [dump](#dump-7)
    * [flushCache](#flushcache-7)
    * [flushStaticCache](#flushstaticcache-7)
    * [jsonSerialize](#jsonserialize-7)
    * [__toString](#__tostring-7)
    * [getInstanceId](#getinstanceid-7)
    * [getSemanticId](#getsemanticid-7)
    * [addMinimalCase](#addminimalcase-7)
    * [setOptions](#setoptions-7)
    * [getOption](#getoption-7)
    * [getOptions](#getoptions-7)
* [BetweenRule](#betweenrule)
    * [rootifyDisjunctions](#rootifydisjunctions-6)
    * [toArray](#toarray-8)
    * [toString](#tostring-8)
    * [removeSameOperationOperands](#removesameoperationoperands-6)
    * [removeInvalidBranches](#removeinvalidbranches-6)
    * [hasSolution](#hassolution-8)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-6)
    * [__construct](#__construct-8)
    * [isSimplified](#issimplified-6)
    * [addOperand](#addoperand-6)
    * [getOperands](#getoperands-6)
    * [setOperands](#setoperands-6)
    * [renameFields](#renamefields-6)
    * [moveSimplificationStepForward](#movesimplificationstepforward-6)
    * [getSimplificationStep](#getsimplificationstep-6)
    * [simplicationStepReached](#simplicationstepreached-6)
    * [removeNegations](#removenegations-6)
    * [cleanOperations](#cleanoperations-6)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-6)
    * [unifyAtomicOperands](#unifyatomicoperands-6)
    * [simplify](#simplify-6)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-6)
    * [copy](#copy-8)
    * [__clone](#__clone-6)
    * [isNormalizationAllowed](#isnormalizationallowed-6)
    * [findSymbolicOperator](#findsymbolicoperator-8)
    * [findEnglishOperator](#findenglishoperator-8)
    * [generateSimpleRule](#generatesimplerule-8)
    * [getRuleClass](#getruleclass-8)
    * [dump](#dump-8)
    * [flushCache](#flushcache-8)
    * [flushStaticCache](#flushstaticcache-8)
    * [jsonSerialize](#jsonserialize-8)
    * [__toString](#__tostring-8)
    * [getInstanceId](#getinstanceid-8)
    * [getSemanticId](#getsemanticid-8)
    * [addMinimalCase](#addminimalcase-8)
    * [setOptions](#setoptions-8)
    * [getOption](#getoption-8)
    * [getOptions](#getoptions-8)
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
    * [hasMatchingCase](#hasmatchingcase)
    * [__construct](#__construct-9)
    * [validateRule](#validaterule)
* [CustomizableMinimalConverter](#customizableminimalconverter)
    * [convert](#convert)
    * [__construct](#__construct-10)
    * [onOpenOr](#onopenor)
    * [onCloseOr](#oncloseor)
    * [onAndPossibility](#onandpossibility)
* [ElasticSearchMinimalConverter](#elasticsearchminimalconverter)
    * [convert](#convert-1)
    * [onOpenOr](#onopenor-1)
    * [onCloseOr](#oncloseor-1)
    * [onAndPossibility](#onandpossibility-1)
* [EqualRule](#equalrule)
    * [toArray](#toarray-9)
    * [toString](#tostring-9)
    * [getField](#getfield-8)
    * [setField](#setfield-4)
    * [renameField](#renamefield-4)
    * [findSymbolicOperator](#findsymbolicoperator-9)
    * [findEnglishOperator](#findenglishoperator-9)
    * [generateSimpleRule](#generatesimplerule-9)
    * [getRuleClass](#getruleclass-9)
    * [copy](#copy-9)
    * [dump](#dump-9)
    * [flushCache](#flushcache-9)
    * [flushStaticCache](#flushstaticcache-9)
    * [jsonSerialize](#jsonserialize-9)
    * [__toString](#__tostring-9)
    * [getInstanceId](#getinstanceid-9)
    * [getSemanticId](#getsemanticid-9)
    * [addMinimalCase](#addminimalcase-9)
    * [setOptions](#setoptions-9)
    * [getOption](#getoption-9)
    * [getOptions](#getoptions-9)
    * [__construct](#__construct-11)
    * [getValue](#getvalue)
    * [getValues](#getvalues-8)
    * [hasSolution](#hassolution-9)
* [FilteredKey](#filteredkey)
* [FilteredValue](#filteredvalue)
* [InlineSqlMinimalConverter](#inlinesqlminimalconverter)
    * [convert](#convert-2)
    * [addParameter](#addparameter)
    * [onOpenOr](#onopenor-2)
    * [onCloseOr](#oncloseor-2)
    * [onAndPossibility](#onandpossibility-2)
* [InRule](#inrule)
    * [removeSameOperationOperands](#removesameoperationoperands-7)
    * [rootifyDisjunctions](#rootifydisjunctions-7)
    * [toArray](#toarray-10)
    * [toString](#tostring-10)
    * [removeInvalidBranches](#removeinvalidbranches-7)
    * [hasSolution](#hassolution-10)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-7)
    * [__construct](#__construct-12)
    * [isSimplified](#issimplified-7)
    * [addOperand](#addoperand-7)
    * [getOperands](#getoperands-7)
    * [setOperands](#setoperands-7)
    * [renameFields](#renamefields-7)
    * [moveSimplificationStepForward](#movesimplificationstepforward-7)
    * [getSimplificationStep](#getsimplificationstep-7)
    * [simplicationStepReached](#simplicationstepreached-7)
    * [removeNegations](#removenegations-7)
    * [cleanOperations](#cleanoperations-7)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-7)
    * [unifyAtomicOperands](#unifyatomicoperands-7)
    * [simplify](#simplify-7)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-7)
    * [copy](#copy-10)
    * [__clone](#__clone-7)
    * [isNormalizationAllowed](#isnormalizationallowed-7)
    * [findSymbolicOperator](#findsymbolicoperator-10)
    * [findEnglishOperator](#findenglishoperator-10)
    * [generateSimpleRule](#generatesimplerule-10)
    * [getRuleClass](#getruleclass-10)
    * [dump](#dump-10)
    * [flushCache](#flushcache-10)
    * [flushStaticCache](#flushstaticcache-10)
    * [jsonSerialize](#jsonserialize-10)
    * [__toString](#__tostring-10)
    * [getInstanceId](#getinstanceid-10)
    * [getSemanticId](#getsemanticid-10)
    * [addMinimalCase](#addminimalcase-10)
    * [setOptions](#setoptions-10)
    * [getOption](#getoption-10)
    * [getOptions](#getoptions-10)
    * [getField](#getfield-9)
    * [setField](#setfield-5)
    * [getPossibilities](#getpossibilities)
    * [addPossibilities](#addpossibilities)
    * [setPossibilities](#setpossibilities)
    * [getValues](#getvalues-9)
* [LogicalFilter](#logicalfilter)
    * [__construct](#__construct-13)
    * [setDefaultOptions](#setdefaultoptions)
    * [getDefaultOptions](#getdefaultoptions)
    * [getOptions](#getoptions-11)
    * [and_](#and_)
    * [or_](#or_)
    * [matches](#matches)
    * [hasSolutionIf](#hassolutionif)
    * [getRules](#getrules)
    * [simplify](#simplify-8)
    * [addMinimalCase](#addminimalcase-11)
    * [hasSolution](#hassolution-11)
    * [toArray](#toarray-11)
    * [toString](#tostring-11)
    * [getSemanticId](#getsemanticid-11)
    * [jsonSerialize](#jsonserialize-11)
    * [__toString](#__tostring-11)
    * [__invoke](#__invoke)
    * [flushRules](#flushrules)
    * [renameFields](#renamefields-8)
    * [removeRules](#removerules)
    * [keepLeafRulesMatching](#keepleafrulesmatching)
    * [listLeafRulesMatching](#listleafrulesmatching)
    * [onEachRule](#oneachrule)
    * [onEachCase](#oneachcase)
    * [getRanges](#getranges)
    * [getFieldRange](#getfieldrange)
    * [copy](#copy-11)
    * [__clone](#__clone-8)
    * [saveAs](#saveas)
    * [saveCopyAs](#savecopyas)
    * [dump](#dump-11)
    * [applyOn](#applyon)
    * [validates](#validates)
* [NotEqualRule](#notequalrule)
    * [getField](#getfield-10)
    * [setField](#setfield-6)
    * [renameField](#renamefield-5)
    * [__construct](#__construct-14)
    * [isNormalizationAllowed](#isnormalizationallowed-8)
    * [negateOperand](#negateoperand)
    * [rootifyDisjunctions](#rootifydisjunctions-8)
    * [unifyAtomicOperands](#unifyatomicoperands-8)
    * [toArray](#toarray-12)
    * [toString](#tostring-12)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-8)
    * [hasSolution](#hassolution-12)
    * [isSimplified](#issimplified-8)
    * [addOperand](#addoperand-8)
    * [getOperands](#getoperands-8)
    * [setOperands](#setoperands-8)
    * [renameFields](#renamefields-9)
    * [moveSimplificationStepForward](#movesimplificationstepforward-8)
    * [getSimplificationStep](#getsimplificationstep-8)
    * [simplicationStepReached](#simplicationstepreached-8)
    * [removeNegations](#removenegations-8)
    * [cleanOperations](#cleanoperations-8)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-8)
    * [simplify](#simplify-9)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-8)
    * [copy](#copy-12)
    * [__clone](#__clone-9)
    * [findSymbolicOperator](#findsymbolicoperator-11)
    * [findEnglishOperator](#findenglishoperator-11)
    * [generateSimpleRule](#generatesimplerule-11)
    * [getRuleClass](#getruleclass-11)
    * [dump](#dump-12)
    * [flushCache](#flushcache-11)
    * [flushStaticCache](#flushstaticcache-11)
    * [jsonSerialize](#jsonserialize-12)
    * [__toString](#__tostring-12)
    * [getInstanceId](#getinstanceid-11)
    * [getSemanticId](#getsemanticid-12)
    * [addMinimalCase](#addminimalcase-12)
    * [setOptions](#setoptions-11)
    * [getOption](#getoption-11)
    * [getOptions](#getoptions-12)
    * [getValue](#getvalue-1)
    * [getValues](#getvalues-10)
* [NotInRule](#notinrule)
    * [__construct](#__construct-15)
    * [isNormalizationAllowed](#isnormalizationallowed-9)
    * [negateOperand](#negateoperand-1)
    * [rootifyDisjunctions](#rootifydisjunctions-9)
    * [unifyAtomicOperands](#unifyatomicoperands-9)
    * [toArray](#toarray-13)
    * [toString](#tostring-13)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-9)
    * [hasSolution](#hassolution-13)
    * [isSimplified](#issimplified-9)
    * [addOperand](#addoperand-9)
    * [getOperands](#getoperands-9)
    * [setOperands](#setoperands-9)
    * [renameFields](#renamefields-10)
    * [moveSimplificationStepForward](#movesimplificationstepforward-9)
    * [getSimplificationStep](#getsimplificationstep-9)
    * [simplicationStepReached](#simplicationstepreached-9)
    * [removeNegations](#removenegations-9)
    * [cleanOperations](#cleanoperations-9)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-9)
    * [simplify](#simplify-10)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-9)
    * [copy](#copy-13)
    * [__clone](#__clone-10)
    * [findSymbolicOperator](#findsymbolicoperator-12)
    * [findEnglishOperator](#findenglishoperator-12)
    * [generateSimpleRule](#generatesimplerule-12)
    * [getRuleClass](#getruleclass-12)
    * [dump](#dump-13)
    * [flushCache](#flushcache-12)
    * [flushStaticCache](#flushstaticcache-12)
    * [jsonSerialize](#jsonserialize-13)
    * [__toString](#__tostring-13)
    * [getInstanceId](#getinstanceid-12)
    * [getSemanticId](#getsemanticid-13)
    * [addMinimalCase](#addminimalcase-13)
    * [setOptions](#setoptions-12)
    * [getOption](#getoption-12)
    * [getOptions](#getoptions-13)
    * [getField](#getfield-11)
    * [setField](#setfield-7)
    * [getPossibilities](#getpossibilities-1)
    * [setPossibilities](#setpossibilities-1)
    * [getValues](#getvalues-11)
* [NotRule](#notrule)
    * [__construct](#__construct-16)
    * [isSimplified](#issimplified-10)
    * [addOperand](#addoperand-10)
    * [getOperands](#getoperands-10)
    * [setOperands](#setoperands-10)
    * [renameFields](#renamefields-11)
    * [moveSimplificationStepForward](#movesimplificationstepforward-10)
    * [getSimplificationStep](#getsimplificationstep-10)
    * [simplicationStepReached](#simplicationstepreached-10)
    * [removeNegations](#removenegations-10)
    * [cleanOperations](#cleanoperations-10)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-10)
    * [unifyAtomicOperands](#unifyatomicoperands-10)
    * [simplify](#simplify-11)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-10)
    * [copy](#copy-14)
    * [__clone](#__clone-11)
    * [isNormalizationAllowed](#isnormalizationallowed-10)
    * [findSymbolicOperator](#findsymbolicoperator-13)
    * [findEnglishOperator](#findenglishoperator-13)
    * [generateSimpleRule](#generatesimplerule-13)
    * [getRuleClass](#getruleclass-13)
    * [dump](#dump-14)
    * [flushCache](#flushcache-13)
    * [flushStaticCache](#flushstaticcache-13)
    * [jsonSerialize](#jsonserialize-14)
    * [__toString](#__tostring-14)
    * [toString](#tostring-14)
    * [toArray](#toarray-14)
    * [getInstanceId](#getinstanceid-13)
    * [getSemanticId](#getsemanticid-14)
    * [addMinimalCase](#addminimalcase-14)
    * [setOptions](#setoptions-13)
    * [getOption](#getoption-13)
    * [getOptions](#getoptions-14)
    * [negateOperand](#negateoperand-2)
    * [rootifyDisjunctions](#rootifydisjunctions-10)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-10)
    * [hasSolution](#hassolution-14)
* [OrRule](#orrule)
    * [__construct](#__construct-17)
    * [isSimplified](#issimplified-11)
    * [addOperand](#addoperand-11)
    * [getOperands](#getoperands-11)
    * [setOperands](#setoperands-11)
    * [renameFields](#renamefields-12)
    * [moveSimplificationStepForward](#movesimplificationstepforward-11)
    * [getSimplificationStep](#getsimplificationstep-11)
    * [simplicationStepReached](#simplicationstepreached-11)
    * [removeNegations](#removenegations-11)
    * [cleanOperations](#cleanoperations-11)
    * [removeMonooperandOperationsOperands](#removemonooperandoperationsoperands-11)
    * [unifyAtomicOperands](#unifyatomicoperands-11)
    * [simplify](#simplify-12)
    * [groupOperandsByFieldAndOperator](#groupoperandsbyfieldandoperator-11)
    * [copy](#copy-15)
    * [__clone](#__clone-12)
    * [isNormalizationAllowed](#isnormalizationallowed-11)
    * [findSymbolicOperator](#findsymbolicoperator-14)
    * [findEnglishOperator](#findenglishoperator-14)
    * [generateSimpleRule](#generatesimplerule-14)
    * [getRuleClass](#getruleclass-14)
    * [dump](#dump-15)
    * [flushCache](#flushcache-14)
    * [flushStaticCache](#flushstaticcache-14)
    * [jsonSerialize](#jsonserialize-15)
    * [__toString](#__tostring-15)
    * [toString](#tostring-15)
    * [toArray](#toarray-15)
    * [getInstanceId](#getinstanceid-14)
    * [getSemanticId](#getsemanticid-15)
    * [addMinimalCase](#addminimalcase-15)
    * [setOptions](#setoptions-14)
    * [getOption](#getoption-14)
    * [getOptions](#getoptions-15)
    * [removeSameOperationOperands](#removesameoperationoperands-8)
    * [rootifyDisjunctions](#rootifydisjunctions-11)
    * [removeInvalidBranches](#removeinvalidbranches-8)
    * [hasSolution](#hassolution-15)
    * [setOperandsOrReplaceByOperation](#setoperandsorreplacebyoperation-11)
* [PhpFilterer](#phpfilterer)
    * [setCustomActions](#setcustomactions-1)
    * [onRowMatches](#onrowmatches-1)
    * [onRowMismatches](#onrowmismatches-1)
    * [getChildren](#getchildren-1)
    * [setChildren](#setchildren-1)
    * [apply](#apply-1)
    * [hasMatchingCase](#hasmatchingcase-1)
    * [validateRule](#validaterule-1)
* [RegexpRule](#regexprule)
    * [toArray](#toarray-16)
    * [toString](#tostring-16)
    * [getField](#getfield-12)
    * [setField](#setfield-8)
    * [renameField](#renamefield-6)
    * [findSymbolicOperator](#findsymbolicoperator-15)
    * [findEnglishOperator](#findenglishoperator-15)
    * [generateSimpleRule](#generatesimplerule-15)
    * [getRuleClass](#getruleclass-15)
    * [copy](#copy-16)
    * [dump](#dump-16)
    * [flushCache](#flushcache-15)
    * [flushStaticCache](#flushstaticcache-15)
    * [jsonSerialize](#jsonserialize-16)
    * [__toString](#__tostring-16)
    * [getInstanceId](#getinstanceid-15)
    * [getSemanticId](#getsemanticid-16)
    * [addMinimalCase](#addminimalcase-16)
    * [setOptions](#setoptions-15)
    * [getOption](#getoption-15)
    * [getOptions](#getoptions-16)
    * [__construct](#__construct-18)
    * [getPattern](#getpattern)
    * [getValues](#getvalues-12)
    * [hasSolution](#hassolution-16)
    * [php2mariadbPCRE](#php2mariadbpcre)
* [RuleFilterer](#rulefilterer)
    * [setCustomActions](#setcustomactions-2)
    * [onRowMatches](#onrowmatches-2)
    * [onRowMismatches](#onrowmismatches-2)
    * [getChildren](#getchildren-2)
    * [setChildren](#setchildren-2)
    * [apply](#apply-2)
    * [hasMatchingCase](#hasmatchingcase-2)
    * [validateRule](#validaterule-2)

## AboveOrEqualRule

a >= x

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\AboveOrEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\OrRule


### getField



```php
AboveOrEqualRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
AboveOrEqualRule::setField(  $field ): string
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
AboveOrEqualRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### removeSameOperationOperands

Remove AndRules operands of AndRules and OrRules of OrRules.

```php
AboveOrEqualRule::removeSameOperationOperands( array $simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
AboveOrEqualRule::rootifyDisjunctions(  $simplification_options ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### toArray



```php
AboveOrEqualRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
AboveOrEqualRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
AboveOrEqualRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
AboveOrEqualRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If the rule can have a solution or not



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
AboveOrEqualRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### __construct



```php
AboveOrEqualRule::__construct( string $field,  $minimum, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$minimum` | **** |  |
| `$options` | **array** |  |




---

### isSimplified



```php
AboveOrEqualRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
AboveOrEqualRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
AboveOrEqualRule::getOperands(  ): array
```





**Return Value:**

$operands



---

### setOperands

Set the minimum and the field of the current instance by giving
an array of opereands as parameter.

```php
AboveOrEqualRule::setOperands( array $operands ): \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |


**Return Value:**

$this



---

### renameFields



```php
AboveOrEqualRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
AboveOrEqualRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
AboveOrEqualRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
AboveOrEqualRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
AboveOrEqualRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
AboveOrEqualRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
AboveOrEqualRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
AboveOrEqualRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
AboveOrEqualRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
AboveOrEqualRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
AboveOrEqualRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
AboveOrEqualRule::__clone(  )
```







---

### isNormalizationAllowed



```php
AboveOrEqualRule::isNormalizationAllowed( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### findSymbolicOperator



```php
AboveOrEqualRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
AboveOrEqualRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
AboveOrEqualRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
AboveOrEqualRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
AboveOrEqualRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
AboveOrEqualRule::flushCache(  )
```







---

### flushStaticCache



```php
AboveOrEqualRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
AboveOrEqualRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
AboveOrEqualRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
AboveOrEqualRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
AboveOrEqualRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
AboveOrEqualRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
AboveOrEqualRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
AboveOrEqualRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
AboveOrEqualRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### getMinimum



```php
AboveOrEqualRule::getMinimum(  ): mixed
```





**Return Value:**

The minimum for the field of this rule



---

### setMinimum

Defines the minimum of the current rule

```php
AboveOrEqualRule::setMinimum( mixed $minimum ): \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$minimum` | **mixed** |  |


**Return Value:**

$this



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


### toArray



```php
AboveRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
AboveRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

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

### findSymbolicOperator



```php
AboveRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
AboveRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
AboveRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




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
AboveRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### dump

Dumps the rule with a chained syntax.

```php
AboveRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
AboveRule::flushCache(  )
```







---

### flushStaticCache



```php
AboveRule::flushStaticCache(  )
```



* This method is **static**.



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

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
AboveRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
AboveRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
AboveRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
AboveRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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



* **Warning:** this method is **deprecated**. This means that this method will likely be removed in a future version.




---

### getLowerLimit



```php
AboveRule::getLowerLimit(  )
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
AboveRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

## AndRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\AndRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractOperationRule

**See Also:**

* https://en.wikipedia.org/wiki/Logical_conjunction 

### __construct



```php
AndRule::__construct( array $operands = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### isSimplified



```php
AndRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
AndRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
AndRule::getOperands(  ): array
```







---

### setOperands



```php
AndRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
AndRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
AndRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
AndRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
AndRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
AndRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
AndRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
AndRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
AndRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
AndRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
AndRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
AndRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
AndRule::__clone(  )
```







---

### isNormalizationAllowed



```php
AndRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
AndRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
AndRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
AndRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
AndRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
AndRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
AndRule::flushCache(  )
```







---

### flushStaticCache



```php
AndRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
AndRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
AndRule::__toString(  ): string
```







---

### toString



```php
AndRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toArray



```php
AndRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
AndRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
AndRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
AndRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
AndRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
AndRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
AndRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
AndRule::rootifyDisjunctions( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule|\JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

The copied operands with one OR at its root



---

### removeSameOperationOperands

Remove AndRules operands of AndRules

```php
AndRule::removeSameOperationOperands(  )
```







---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
AndRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

$this



---

### hasSolution

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2

```php
AndRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

If the AndRule can have a solution or not



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
AndRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new AndRule



---

## BelowOrEqualRule

a <= x

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\OrRule


### getField



```php
BelowOrEqualRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
BelowOrEqualRule::setField(  $field ): string
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
BelowOrEqualRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### removeSameOperationOperands

Remove AndRules operands of AndRules and OrRules of OrRules.

```php
BelowOrEqualRule::removeSameOperationOperands( array $simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BelowOrEqualRule::rootifyDisjunctions(  $simplification_options ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### toArray



```php
BelowOrEqualRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
BelowOrEqualRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
BelowOrEqualRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
BelowOrEqualRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If the rule can have a solution or not



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
BelowOrEqualRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### __construct



```php
BelowOrEqualRule::__construct( string $field,  $maximum, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$maximum` | **** |  |
| `$options` | **array** |  |




---

### isSimplified



```php
BelowOrEqualRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
BelowOrEqualRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
BelowOrEqualRule::getOperands(  ): array
```





**Return Value:**

$operands



---

### setOperands

Set the maximum and the field of the current instance by giving
an array of opereands as parameter.

```php
BelowOrEqualRule::setOperands( array $operands ): \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |


**Return Value:**

$this



---

### renameFields



```php
BelowOrEqualRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
BelowOrEqualRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
BelowOrEqualRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
BelowOrEqualRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
BelowOrEqualRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
BelowOrEqualRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
BelowOrEqualRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
BelowOrEqualRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
BelowOrEqualRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
BelowOrEqualRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
BelowOrEqualRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
BelowOrEqualRule::__clone(  )
```







---

### isNormalizationAllowed



```php
BelowOrEqualRule::isNormalizationAllowed( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### findSymbolicOperator



```php
BelowOrEqualRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BelowOrEqualRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BelowOrEqualRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
BelowOrEqualRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
BelowOrEqualRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BelowOrEqualRule::flushCache(  )
```







---

### flushStaticCache



```php
BelowOrEqualRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BelowOrEqualRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BelowOrEqualRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BelowOrEqualRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BelowOrEqualRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BelowOrEqualRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BelowOrEqualRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BelowOrEqualRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BelowOrEqualRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### getMaximum



```php
BelowOrEqualRule::getMaximum(  ): mixed
```





**Return Value:**

The maximum for the field of this rule



---

### setMaximum

Defines the maximum of the current rule

```php
BelowOrEqualRule::setMaximum( mixed $maximum ): \JClaveau\LogicalFilter\Rule\BelowOrEqualRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$maximum` | **mixed** |  |


**Return Value:**

$this



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


### toArray



```php
BelowRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
BelowRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

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

### findSymbolicOperator



```php
BelowRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BelowRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BelowRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




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
BelowRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### dump

Dumps the rule with a chained syntax.

```php
BelowRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BelowRule::flushCache(  )
```







---

### flushStaticCache



```php
BelowRule::flushStaticCache(  )
```



* This method is **static**.



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

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BelowRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BelowRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BelowRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BelowRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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
BelowRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### getMaximum



```php
BelowRule::getMaximum(  )
```



* **Warning:** this method is **deprecated**. This means that this method will likely be removed in a future version.




---

### getUpperLimit



```php
BelowRule::getUpperLimit(  )
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
BetweenOrEqualBothRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualBothRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
BetweenOrEqualBothRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualBothRule::rootifyDisjunctions( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule|\JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

The copied operands with one OR at its root



---

### removeSameOperationOperands

Remove AndRules operands of AndRules

```php
BetweenOrEqualBothRule::removeSameOperationOperands(  )
```







---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualBothRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

$this



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
BetweenOrEqualBothRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new AndRule



---

### isSimplified



```php
BetweenOrEqualBothRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
BetweenOrEqualBothRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
BetweenOrEqualBothRule::getOperands(  ): array
```







---

### setOperands



```php
BetweenOrEqualBothRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
BetweenOrEqualBothRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
BetweenOrEqualBothRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
BetweenOrEqualBothRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
BetweenOrEqualBothRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
BetweenOrEqualBothRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
BetweenOrEqualBothRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
BetweenOrEqualBothRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
BetweenOrEqualBothRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
BetweenOrEqualBothRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
BetweenOrEqualBothRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
BetweenOrEqualBothRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
BetweenOrEqualBothRule::__clone(  )
```







---

### isNormalizationAllowed



```php
BetweenOrEqualBothRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
BetweenOrEqualBothRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BetweenOrEqualBothRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BetweenOrEqualBothRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
BetweenOrEqualBothRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
BetweenOrEqualBothRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BetweenOrEqualBothRule::flushCache(  )
```







---

### flushStaticCache



```php
BetweenOrEqualBothRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BetweenOrEqualBothRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BetweenOrEqualBothRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BetweenOrEqualBothRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BetweenOrEqualBothRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BetweenOrEqualBothRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BetweenOrEqualBothRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BetweenOrEqualBothRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BetweenOrEqualBothRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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
BetweenOrEqualLowerRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualLowerRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
BetweenOrEqualLowerRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualLowerRule::rootifyDisjunctions( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule|\JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

The copied operands with one OR at its root



---

### removeSameOperationOperands

Remove AndRules operands of AndRules

```php
BetweenOrEqualLowerRule::removeSameOperationOperands(  )
```







---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualLowerRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

$this



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
BetweenOrEqualLowerRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new AndRule



---

### isSimplified



```php
BetweenOrEqualLowerRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
BetweenOrEqualLowerRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
BetweenOrEqualLowerRule::getOperands(  ): array
```







---

### setOperands



```php
BetweenOrEqualLowerRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
BetweenOrEqualLowerRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
BetweenOrEqualLowerRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
BetweenOrEqualLowerRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
BetweenOrEqualLowerRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
BetweenOrEqualLowerRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
BetweenOrEqualLowerRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
BetweenOrEqualLowerRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
BetweenOrEqualLowerRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
BetweenOrEqualLowerRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
BetweenOrEqualLowerRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
BetweenOrEqualLowerRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
BetweenOrEqualLowerRule::__clone(  )
```







---

### isNormalizationAllowed



```php
BetweenOrEqualLowerRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
BetweenOrEqualLowerRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BetweenOrEqualLowerRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BetweenOrEqualLowerRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
BetweenOrEqualLowerRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
BetweenOrEqualLowerRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BetweenOrEqualLowerRule::flushCache(  )
```







---

### flushStaticCache



```php
BetweenOrEqualLowerRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BetweenOrEqualLowerRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BetweenOrEqualLowerRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BetweenOrEqualLowerRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BetweenOrEqualLowerRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BetweenOrEqualLowerRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BetweenOrEqualLowerRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BetweenOrEqualLowerRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BetweenOrEqualLowerRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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
BetweenOrEqualUpperRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

If the AndRule can have a solution or not



---

### toArray



```php
BetweenOrEqualUpperRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
BetweenOrEqualUpperRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenOrEqualUpperRule::rootifyDisjunctions( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule|\JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

The copied operands with one OR at its root



---

### removeSameOperationOperands

Remove AndRules operands of AndRules

```php
BetweenOrEqualUpperRule::removeSameOperationOperands(  )
```







---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenOrEqualUpperRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

$this



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
BetweenOrEqualUpperRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new AndRule



---

### isSimplified



```php
BetweenOrEqualUpperRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
BetweenOrEqualUpperRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
BetweenOrEqualUpperRule::getOperands(  ): array
```







---

### setOperands



```php
BetweenOrEqualUpperRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
BetweenOrEqualUpperRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
BetweenOrEqualUpperRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
BetweenOrEqualUpperRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
BetweenOrEqualUpperRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
BetweenOrEqualUpperRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
BetweenOrEqualUpperRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
BetweenOrEqualUpperRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
BetweenOrEqualUpperRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
BetweenOrEqualUpperRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
BetweenOrEqualUpperRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
BetweenOrEqualUpperRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
BetweenOrEqualUpperRule::__clone(  )
```







---

### isNormalizationAllowed



```php
BetweenOrEqualUpperRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
BetweenOrEqualUpperRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BetweenOrEqualUpperRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BetweenOrEqualUpperRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
BetweenOrEqualUpperRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
BetweenOrEqualUpperRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BetweenOrEqualUpperRule::flushCache(  )
```







---

### flushStaticCache



```php
BetweenOrEqualUpperRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BetweenOrEqualUpperRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BetweenOrEqualUpperRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BetweenOrEqualUpperRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BetweenOrEqualUpperRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BetweenOrEqualUpperRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BetweenOrEqualUpperRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BetweenOrEqualUpperRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BetweenOrEqualUpperRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

## BetweenRule

Logical conjunction:



* Full name: \JClaveau\LogicalFilter\Rule\BetweenRule
* Parent class: \JClaveau\LogicalFilter\Rule\AndRule


### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
BetweenRule::rootifyDisjunctions( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule|\JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

The copied operands with one OR at its root



---

### toArray



```php
BetweenRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
BetweenRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### removeSameOperationOperands

Remove AndRules operands of AndRules

```php
BetweenRule::removeSameOperationOperands(  )
```







---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1

```php
BetweenRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

$this



---

### hasSolution

Checks if the range is valid.

```php
BetweenRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
BetweenRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\AndRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new AndRule



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

### isSimplified



```php
BetweenRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
BetweenRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
BetweenRule::getOperands(  ): array
```







---

### setOperands



```php
BetweenRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
BetweenRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
BetweenRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
BetweenRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
BetweenRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
BetweenRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
BetweenRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
BetweenRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
BetweenRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
BetweenRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
BetweenRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
BetweenRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
BetweenRule::__clone(  )
```







---

### isNormalizationAllowed



```php
BetweenRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
BetweenRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
BetweenRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
BetweenRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
BetweenRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
BetweenRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
BetweenRule::flushCache(  )
```







---

### flushStaticCache



```php
BetweenRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
BetweenRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
BetweenRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
BetweenRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
BetweenRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
BetweenRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
BetweenRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
BetweenRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
BetweenRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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
CustomizableFilterer::apply( \JClaveau\LogicalFilter\LogicalFilter $filter, \JClaveau\LogicalFilter\Filterer\Iterable $tree_to_filter, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$tree_to_filter` | **\JClaveau\LogicalFilter\Filterer\Iterable** |  |
| `$options` | **array** |  |




---

### hasMatchingCase



```php
CustomizableFilterer::hasMatchingCase( \JClaveau\LogicalFilter\LogicalFilter $filter,  $row_to_check,  $key_to_check, array $options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$row_to_check` | **** |  |
| `$key_to_check` | **** |  |
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
CustomizableFilterer::validateRule(  $field,  $operator,  $value,  $row, array $path,  $all_operands,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$row` | **** |  |
| `$path` | **array** |  |
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


### toArray



```php
EqualRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
EqualRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

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

### findSymbolicOperator



```php
EqualRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
EqualRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
EqualRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




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
EqualRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### dump

Dumps the rule with a chained syntax.

```php
EqualRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
EqualRule::flushCache(  )
```







---

### flushStaticCache



```php
EqualRule::flushStaticCache(  )
```



* This method is **static**.



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

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
EqualRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
EqualRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
EqualRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
EqualRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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
EqualRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

## FilteredKey





* Full name: \JClaveau\LogicalFilter\FilteredKey
* Parent class: 


## FilteredValue





* Full name: \JClaveau\LogicalFilter\FilteredValue
* Parent class: 


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

### addParameter



```php
InlineSqlMinimalConverter::addParameter(  $value ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **** |  |


**Return Value:**

parameter id



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


### removeSameOperationOperands

Remove AndRules operands of AndRules and OrRules of OrRules.

```php
InRule::removeSameOperationOperands( array $simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
InRule::rootifyDisjunctions(  $simplification_options ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### toArray



```php
InRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
InRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
InRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
InRule::hasSolution( array $contextual_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

If the InRule can have a solution or not



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
InRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### __construct



```php
InRule::__construct( string $field, mixed $possibilities, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$possibilities` | **mixed** | The values the field can belong to. |
| `$options` | **array** |  |




---

### isSimplified



```php
InRule::isSimplified(  ): boolean
```







---

### addOperand



```php
InRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $operand ): \JClaveau\LogicalFilter\Rule\InRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |


**Return Value:**

$this



---

### getOperands



```php
InRule::getOperands(  ): \JClaveau\LogicalFilter\Rule\InRule
```





**Return Value:**

$this



---

### setOperands



```php
InRule::setOperands( array $operands ): \JClaveau\LogicalFilter\Rule\InRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |


**Return Value:**

$this



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

### moveSimplificationStepForward



```php
InRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
InRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
InRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

There is no negations into an InRule

```php
InRule::removeNegations( array $contextual_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
InRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
InRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
InRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
InRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
InRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
InRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
InRule::__clone(  )
```







---

### isNormalizationAllowed



```php
InRule::isNormalizationAllowed( array $contextual_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### findSymbolicOperator



```php
InRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
InRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
InRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
InRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
InRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
InRule::flushCache(  )
```







---

### flushStaticCache



```php
InRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
InRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
InRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
InRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
InRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
InRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
InRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
InRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
InRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### getField



```php
InRule::getField(  ): string
```





**Return Value:**

The field



---

### setField



```php
InRule::setField(  $field ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |




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
LogicalFilter::__construct( array $rules = array(), \JClaveau\LogicalFilter\Filterer\Filterer $default_filterer = null, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rules` | **array** |  |
| `$default_filterer` | **\JClaveau\LogicalFilter\Filterer\Filterer** |  |
| `$options` | **array** |  |



**See Also:**

* self::addRules 

---

### setDefaultOptions



```php
LogicalFilter::setDefaultOptions( array $options )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getDefaultOptions



```php
LogicalFilter::getDefaultOptions(  ): array
```



* This method is **static**.



---

### getOptions



```php
LogicalFilter::getOptions(  ): array
```







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

### matches



```php
LogicalFilter::matches(  $rules_to_match )
```



* **Warning:** this method is **deprecated**. This means that this method will likely be removed in a future version.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rules_to_match` | **** |  |




---

### hasSolutionIf

Checks that a filter matches another one.

```php
LogicalFilter::hasSolutionIf( array|\JClaveau\LogicalFilter\Rule\AbstractRule $rules_to_match ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rules_to_match` | **array&#124;\JClaveau\LogicalFilter\Rule\AbstractRule** |  |


**Return Value:**

Whether or not this combination of filters has
             potential solutions



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

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
LogicalFilter::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





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
LogicalFilter::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |


**Return Value:**

A description of the rules.



---

### toString

Returns an array describing the rule tree of the Filter.

```php
LogicalFilter::toString( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |


**Return Value:**

A description of the rules.



---

### getSemanticId

Returns a unique id corresponding to the set of rules of the filter

```php
LogicalFilter::getSemanticId(  ): string
```





**Return Value:**

The unique semantic id



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
LogicalFilter::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
LogicalFilter::__toString(  ): string
```







---

### __invoke



```php
LogicalFilter::__invoke( mixed $row,  $key = null ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **mixed** |  |
| `$key` | **** |  |



**See Also:**

* https://secure.php.net/manual/en/language.oop5.magic.php#object.invoke 

---

### flushRules

Removes all the defined rules.

```php
LogicalFilter::flushRules(  ): $this
```







---

### renameFields



```php
LogicalFilter::renameFields(  $renamings ): \JClaveau\LogicalFilter\LogicalFilter
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

$filter->onEachRule(
     ['field', 'in', [.

```php
LogicalFilter::onEachRule(  $filter = array(),  $options ): array
```

..]],
     function ($rule, $key, array &$rules) {
         // ...
})

$filter->onEachRule(
     ['field', 'in', [...]],
     [
         Filterer::on_row_matches => function ($rule, $key, array &$rules) {
             // ...
         },
         Filterer::on_row_mismatches => function ($rule, $key, array &$rules) {
             // ...
         },
     ]
)


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **** |  |
| `$options` | **** |  |


**Return Value:**

The rules matching the filter



---

### onEachCase

$filter->onEachCase(function (AndRule $case, $key, array &$caseRules) {
     // do whatever you want on the current case.

```php
LogicalFilter::onEachCase( array|callable $action ): \JClaveau\LogicalFilter\LogicalFilter
```

..
})


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$action` | **array&#124;callable** | Callback to apply on each case. |


**Return Value:**

$this



---

### getRanges

Retrieves the minimum possibility and the maximum possibility for
each field of the rules matching the filter.

```php
LogicalFilter::getRanges( array|\JClaveau\LogicalFilter\LogicalFilter|\JClaveau\LogicalFilter\Rule\AbstractRule $ruleFilter = null ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleFilter` | **array&#124;\JClaveau\LogicalFilter\LogicalFilter&#124;\JClaveau\LogicalFilter\Rule\AbstractRule** |  |


**Return Value:**

The bounds of the range and a nullable property for each field



---

### getFieldRange

Retrieves the minimum possibility and the maximum possibility for
the given field.

```php
LogicalFilter::getFieldRange( mixed $field ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **mixed** |  |


**Return Value:**

The bounds of the range and a nullable property for the given field



---

### copy

Clone the current object and its rules.

```php
LogicalFilter::copy(  ): \JClaveau\LogicalFilter\LogicalFilter
```





**Return Value:**

A copy of the current instance with a copied ruletree



---

### __clone

Make a deep copy of the rules

```php
LogicalFilter::__clone(  )
```







---

### saveAs

Copy the current instance into the variable given as parameter
and returns the copy.

```php
LogicalFilter::saveAs(  &$variable ): \JClaveau\LogicalFilter\LogicalFilter
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$variable` | **** |  |




---

### saveCopyAs

Copy the current instance into the variable given as parameter
and returns the copied instance.

```php
LogicalFilter::saveCopyAs(  &$copied_variable ): \JClaveau\LogicalFilter\LogicalFilter
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$copied_variable` | **** |  |




---

### dump



```php
LogicalFilter::dump(  $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **** |  |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' |




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

### validates

Applies the current instance to a value (and its index optionnally).

```php
LogicalFilter::validates( mixed $value_to_check,  $key_to_check = null, \JClaveau\LogicalFilter\Filterer\Filterer|callable|null $filterer = null ): \JClaveau\LogicalFilter\Rule\AbstractRule|false|true
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value_to_check` | **mixed** |  |
| `$key_to_check` | **** |  |
| `$filterer` | **\JClaveau\LogicalFilter\Filterer\Filterer&#124;callable&#124;null** |  |


**Return Value:**

+ False if the filter doesn't validates
                                + Null if the target has no sens (operation filtered by field for example)
                                + A rule tree containing the first matching case if there is one.



---

## NotEqualRule

a != x



* Full name: \JClaveau\LogicalFilter\Rule\NotEqualRule
* Parent class: \JClaveau\LogicalFilter\Rule\NotRule


### getField



```php
NotEqualRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
NotEqualRule::setField(  $field ): string
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
NotEqualRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### __construct



```php
NotEqualRule::__construct( string $field, mixed $value, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$value` | **mixed** | The value the field can equal to. |
| `$options` | **array** |  |




---

### isNormalizationAllowed



```php
NotEqualRule::isNormalizationAllowed( array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotEqualRule::negateOperand( array $current_simplification_options ): \JClaveau\LogicalFilter\Rule\AbstractRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### rootifyDisjunctions



```php
NotEqualRule::rootifyDisjunctions(  $simplification_options ): \JClaveau\LogicalFilter\Rule\NotRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
NotEqualRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### toArray



```php
NotEqualRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
NotEqualRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
NotEqualRule::setOperandsOrReplaceByOperation( array&lt;mixed,\JClaveau\LogicalFilter\Rule\AbstractRule&gt; $new_operands, array $contextual_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **array<mixed,\JClaveau\LogicalFilter\Rule\AbstractRule>** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### hasSolution

By default, every atomic rule can have a solution by itself

```php
NotEqualRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### isSimplified



```php
NotEqualRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
NotEqualRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
NotEqualRule::getOperands(  ): array
```







---

### setOperands



```php
NotEqualRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
NotEqualRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
NotEqualRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
NotEqualRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
NotEqualRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
NotEqualRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
NotEqualRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
NotEqualRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### simplify

Simplify the current OperationRule.

```php
NotEqualRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
NotEqualRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
NotEqualRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
NotEqualRule::__clone(  )
```







---

### findSymbolicOperator



```php
NotEqualRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
NotEqualRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
NotEqualRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
NotEqualRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
NotEqualRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
NotEqualRule::flushCache(  )
```







---

### flushStaticCache



```php
NotEqualRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
NotEqualRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
NotEqualRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
NotEqualRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
NotEqualRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
NotEqualRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
NotEqualRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
NotEqualRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
NotEqualRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




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

## NotInRule

a ! in x



* Full name: \JClaveau\LogicalFilter\Rule\NotInRule
* Parent class: \JClaveau\LogicalFilter\Rule\NotRule


### __construct



```php
NotInRule::__construct( string $field,  $possibilities )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$possibilities` | **** |  |




---

### isNormalizationAllowed



```php
NotInRule::isNormalizationAllowed( array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotInRule::negateOperand( array $current_simplification_options ): \JClaveau\LogicalFilter\Rule\AbstractRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### rootifyDisjunctions



```php
NotInRule::rootifyDisjunctions(  $simplification_options ): \JClaveau\LogicalFilter\Rule\NotRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
NotInRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### toArray



```php
NotInRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### toString



```php
NotInRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
NotInRule::setOperandsOrReplaceByOperation( array&lt;mixed,\JClaveau\LogicalFilter\Rule\AbstractRule&gt; $new_operands, array $contextual_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **array<mixed,\JClaveau\LogicalFilter\Rule\AbstractRule>** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### hasSolution



```php
NotInRule::hasSolution( array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

### isSimplified



```php
NotInRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
NotInRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
NotInRule::getOperands(  ): array
```







---

### setOperands



```php
NotInRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
NotInRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
NotInRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
NotInRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
NotInRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
NotInRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
NotInRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
NotInRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### simplify

Simplify the current OperationRule.

```php
NotInRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
NotInRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
NotInRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
NotInRule::__clone(  )
```







---

### findSymbolicOperator



```php
NotInRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
NotInRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
NotInRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
NotInRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
NotInRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
NotInRule::flushCache(  )
```







---

### flushStaticCache



```php
NotInRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
NotInRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
NotInRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
NotInRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
NotInRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
NotInRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
NotInRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
NotInRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
NotInRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### getField



```php
NotInRule::getField(  )
```







---

### setField



```php
NotInRule::setField(  $field )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |




---

### getPossibilities



```php
NotInRule::getPossibilities(  ): array
```







---

### setPossibilities



```php
NotInRule::setPossibilities(  $possibilities ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$possibilities` | **** |  |




---

### getValues



```php
NotInRule::getValues(  )
```







---

## NotRule

Logical negation:



* Full name: \JClaveau\LogicalFilter\Rule\NotRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractOperationRule

**See Also:**

* https://en.wikipedia.org/wiki/Negation 

### __construct



```php
NotRule::__construct( \JClaveau\LogicalFilter\Rule\AbstractRule $operand = null, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |
| `$options` | **array** |  |




---

### isSimplified



```php
NotRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
NotRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
NotRule::getOperands(  ): array
```







---

### setOperands



```php
NotRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
NotRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
NotRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
NotRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
NotRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
NotRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
NotRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
NotRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Not rules can only have one operand.

```php
NotRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |




---

### simplify

Simplify the current OperationRule.

```php
NotRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
NotRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
NotRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
NotRule::__clone(  )
```







---

### isNormalizationAllowed



```php
NotRule::isNormalizationAllowed( array $current_simplification_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
NotRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
NotRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
NotRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
NotRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
NotRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
NotRule::flushCache(  )
```







---

### flushStaticCache



```php
NotRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
NotRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
NotRule::__toString(  ): string
```







---

### toString



```php
NotRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toArray



```php
NotRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
NotRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
NotRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
NotRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
NotRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
NotRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
NotRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### negateOperand

Transforms all composite rules in the tree of operands into
atomic rules.

```php
NotRule::negateOperand( array $current_simplification_options ): \JClaveau\LogicalFilter\Rule\AbstractRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### rootifyDisjunctions



```php
NotRule::rootifyDisjunctions(  $simplification_options ): \JClaveau\LogicalFilter\Rule\NotRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
NotRule::setOperandsOrReplaceByOperation( array&lt;mixed,\JClaveau\LogicalFilter\Rule\AbstractRule&gt; $new_operands, array $contextual_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **array<mixed,\JClaveau\LogicalFilter\Rule\AbstractRule>** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



---

### hasSolution



```php
NotRule::hasSolution( array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |




---

## OrRule

Logical inclusive disjunction

This class represents a rule that expect a value to be one of the list of
possibilities only.

* Full name: \JClaveau\LogicalFilter\Rule\OrRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractOperationRule


### __construct



```php
OrRule::__construct( array $operands = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### isSimplified



```php
OrRule::isSimplified(  ): boolean
```







---

### addOperand

Adds an operand to the logical operation (&& or ||).

```php
OrRule::addOperand( \JClaveau\LogicalFilter\Rule\AbstractRule $new_operand ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operand` | **\JClaveau\LogicalFilter\Rule\AbstractRule** |  |




---

### getOperands



```php
OrRule::getOperands(  ): array
```







---

### setOperands



```php
OrRule::setOperands( array $operands ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$operands` | **array** |  |




---

### renameFields



```php
OrRule::renameFields( array|callable $renamings ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **array&#124;callable** | Associative array of renamings or callable
                                  that would rename the fields. |


**Return Value:**

$this



---

### moveSimplificationStepForward



```php
OrRule::moveSimplificationStepForward( string $step_to_go_to, array $simplification_options, boolean $force = false )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step_to_go_to` | **string** |  |
| `$simplification_options` | **array** |  |
| `$force` | **boolean** |  |




---

### getSimplificationStep



```php
OrRule::getSimplificationStep(  ): string
```





**Return Value:**

The current simplification step



---

### simplicationStepReached

Checks if a simplification step is reached.

```php
OrRule::simplicationStepReached( string $step ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$step` | **string** |  |




---

### removeNegations

Replace NotRule objects by the negation of their operands.

```php
OrRule::removeNegations( array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **array** |  |


**Return Value:**

$this or a $new rule with negations removed



---

### cleanOperations

Operation cleaning consists of removing operation with one operand
and removing operations having a same type of operation as operand.

```php
OrRule::cleanOperations( array $simplification_options, boolean $recurse = true ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```

This operation has been required between every steps until now.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |
| `$recurse` | **boolean** |  |




---

### removeMonooperandOperationsOperands

If a child is an OrRule or an AndRule and has only one child,
replace it by its child.

```php
OrRule::removeMonooperandOperationsOperands( array $simplification_options ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If something has been simplified or not



---

### unifyAtomicOperands

Removes duplicates between the current AbstractOperationRule.

```php
OrRule::unifyAtomicOperands(  $simplification_strategy_step = false, array $contextual_options ): \JClaveau\LogicalFilter\Rule\AbstractOperationRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_strategy_step` | **** |  |
| `$contextual_options` | **array** |  |


**Return Value:**

the simplified rule



---

### simplify

Simplify the current OperationRule.

```php
OrRule::simplify( array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | stop_after &#124; stop_before &#124; force_logical_core |


**Return Value:**

the simplified rule



---

### groupOperandsByFieldAndOperator

Indexes operands by their fields and operators. This sorting is
used during the simplification step.

```php
OrRule::groupOperandsByFieldAndOperator(  ): array
```





**Return Value:**

The 3 dimensions array of operands: field > operator > i



---

### copy

Clones the rule with a chained syntax.

```php
OrRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### __clone

Make a deep copy of operands

```php
OrRule::__clone(  )
```







---

### isNormalizationAllowed



```php
OrRule::isNormalizationAllowed( array $current_simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$current_simplification_options` | **array** |  |




---

### findSymbolicOperator



```php
OrRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
OrRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
OrRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
OrRule::getRuleClass( string $rule_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rule_operator` | **string** |  |


**Return Value:**

Class corresponding to the given operator



---

### dump

Dumps the rule with a chained syntax.

```php
OrRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
OrRule::flushCache(  )
```







---

### flushStaticCache



```php
OrRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
OrRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
OrRule::__toString(  ): string
```







---

### toString



```php
OrRule::toString( array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toArray



```php
OrRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | + show_instance=false Display the operator of the rule or its instance id |




---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
OrRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
OrRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
OrRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
OrRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
OrRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
OrRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### removeSameOperationOperands

Remove AndRules operands of AndRules and OrRules of OrRules.

```php
OrRule::removeSameOperationOperands( array $simplification_options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### rootifyDisjunctions

Replace all the OrRules of the RuleTree by one OrRule at its root.

```php
OrRule::rootifyDisjunctions(  $simplification_options ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **** |  |




---

### removeInvalidBranches

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1

```php
OrRule::removeInvalidBranches( array $simplification_options ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### hasSolution

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.

```php
OrRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |


**Return Value:**

If the rule can have a solution or not



---

### setOperandsOrReplaceByOperation

This method is meant to be used during simplification that would
need to change the class of the current instance by a normal one.

```php
OrRule::setOperandsOrReplaceByOperation(  $new_operands ): \JClaveau\LogicalFilter\Rule\OrRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$new_operands` | **** |  |


**Return Value:**

The current instance (of or or subclass) or a new OrRule



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
PhpFilterer::apply( \JClaveau\LogicalFilter\LogicalFilter $filter, \JClaveau\LogicalFilter\Filterer\Iterable $tree_to_filter, array $options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$tree_to_filter` | **\JClaveau\LogicalFilter\Filterer\Iterable** |  |
| `$options` | **array** |  |




---

### hasMatchingCase



```php
PhpFilterer::hasMatchingCase( \JClaveau\LogicalFilter\LogicalFilter $filter,  $row_to_check,  $key_to_check, array $options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$row_to_check` | **** |  |
| `$key_to_check` | **** |  |
| `$options` | **array** |  |




---

### validateRule



```php
PhpFilterer::validateRule(  $field,  $operator,  $value,  $row, array $path,  $all_operands,  $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$row` | **** |  |
| `$path` | **array** |  |
| `$all_operands` | **** |  |
| `$options` | **** |  |




---

## RegexpRule

a regexp /^.

..$/

RegexpRule follow the PCRE syntax as PHP and MariaDb.

* Full name: \JClaveau\LogicalFilter\Rule\RegexpRule
* Parent class: \JClaveau\LogicalFilter\Rule\AbstractAtomicRule

**See Also:**

* https://mariadb.com/kb/en/library/pcre/ 

### toArray



```php
RegexpRule::toArray( array $options = array() ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### toString



```php
RegexpRule::toString( array $options = array() ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getField



```php
RegexpRule::getField(  ): string
```





**Return Value:**

$field



---

### setField



```php
RegexpRule::setField(  $field ): string
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
RegexpRule::renameField(  $renamings ): \JClaveau\LogicalFilter\Rule\AbstractAtomicRule
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$renamings` | **** |  |


**Return Value:**

$this



---

### findSymbolicOperator



```php
RegexpRule::findSymbolicOperator( string $english_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$english_operator` | **string** |  |




---

### findEnglishOperator



```php
RegexpRule::findEnglishOperator( string $symbolic_operator ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$symbolic_operator` | **string** |  |




---

### generateSimpleRule



```php
RegexpRule::generateSimpleRule( string $field, string $type, mixed $values, array $options = array() ): \JClaveau\LogicalFilter\Rule\AbstractRule
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$type` | **string** |  |
| `$values` | **mixed** |  |
| `$options` | **array** |  |




---

### getRuleClass



```php
RegexpRule::getRuleClass( string $rule_operator ): string
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
RegexpRule::copy(  ): \JClaveau\LogicalFilter\Rule\AbstractRule
```





**Return Value:**

A copy of the current instance.



---

### dump

Dumps the rule with a chained syntax.

```php
RegexpRule::dump( boolean $exit = false, array $options = array() ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exit` | **boolean** | Default false |
| `$options` | **array** | + callstack_depth=2 The level of the caller to dump
                         + mode='string' in 'export' &#124; 'dump' &#124; 'string' &#124; 'xdebug' |




---

### flushCache



```php
RegexpRule::flushCache(  )
```







---

### flushStaticCache



```php
RegexpRule::flushStaticCache(  )
```



* This method is **static**.



---

### jsonSerialize

For implementing JsonSerializable interface.

```php
RegexpRule::jsonSerialize(  )
```






**See Also:**

* https://secure.php.net/manual/en/jsonserializable.jsonserialize.php 

---

### __toString



```php
RegexpRule::__toString(  ): string
```







---

### getInstanceId

Returns an id describing the instance internally for debug purpose.

```php
RegexpRule::getInstanceId(  ): string
```






**See Also:**

* https://secure.php.net/manual/en/function.spl-object-id.php 

---

### getSemanticId

Returns an id corresponding to the meaning of the rule.

```php
RegexpRule::getSemanticId(  ): string
```







---

### addMinimalCase

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

```php
RegexpRule::addMinimalCase(  ): \JClaveau\LogicalFilter\Rule\OrRule
```

This helpes to ease the result of simplify()





---

### setOptions



```php
RegexpRule::setOptions( array $options )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |




---

### getOption



```php
RegexpRule::getOption(  $name, array $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **** |  |
| `$contextual_options` | **array** |  |




---

### getOptions



```php
RegexpRule::getOptions(  $contextual_options = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contextual_options` | **** |  |




---

### __construct



```php
RegexpRule::__construct( string $field, array $pattern )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | The field to apply the rule on. |
| `$pattern` | **array** | The regular expression to match. |




---

### getPattern



```php
RegexpRule::getPattern(  )
```







---

### getValues



```php
RegexpRule::getValues(  ): array
```







---

### hasSolution

By default, every atomic rule can have a solution by itself

```php
RegexpRule::hasSolution( array $simplification_options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$simplification_options` | **array** |  |




---

### php2mariadbPCRE

Removes the delimiter and write the options in a MariaDB way.

```php
RegexpRule::php2mariadbPCRE(  $php_regexp ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$php_regexp` | **** |  |


**Return Value:**

The pattern in a MariaDB syntax


**See Also:**

* https://mariadb.com/kb/en/library/pcre/ 

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
| `$options` | **array** | leaves_only &#124; debug |




---

### hasMatchingCase



```php
RuleFilterer::hasMatchingCase( \JClaveau\LogicalFilter\LogicalFilter $filter,  $row_to_check,  $key_to_check, array $options = array() ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filter` | **\JClaveau\LogicalFilter\LogicalFilter** |  |
| `$row_to_check` | **** |  |
| `$key_to_check` | **** |  |
| `$options` | **array** |  |




---

### validateRule



```php
RuleFilterer::validateRule(  $field,  $operator,  $value,  $rule, array $path,  $all_operands,  $options ): true
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **** |  |
| `$operator` | **** |  |
| `$value` | **** |  |
| `$rule` | **** |  |
| `$path` | **array** |  |
| `$all_operands` | **** |  |
| `$options` | **** |  |


**Return Value:**

| false | null



---



--------
> This document was automatically generated from source code comments on 2019-02-06 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
