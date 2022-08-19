<?php

if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

class MeiLiBo_Sniffs_Classes_ValidMethodNameSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{

    protected $magicMethods = array(
        'construct',
        'destruct',
        'call',
        'callstatic',
        'get',
        'set',
        'isset',
        'unset',
        'sleep',
        'wakeup',
        'tostring',
        'set_state',
        'clone',
        'invoke',
        'call',
    );

    public function __construct()
    {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION));

    }//end __construct()


    /**
     * Processes the function tokens within the class.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int $stackPtr The position where the token was found.
     * @param int $currScope The current scope opener token.
     *
     * @return void
     */
    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $tokens = $phpcsFile->getTokens();

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = strtolower(substr($methodName, 2));
            if (in_array($magicPart, $this->magicMethods) === false) {
                $error = 'Method name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore';
                $phpcsFile->addError($error, $stackPtr, 'MethodDoubleUnderscore', $errorData);
            }

            return;
        }

        $valid = PHP_CodeSniffer::isCamelCaps($methodName, true, true, false) || PHP_CodeSniffer::isUnderscoreName(ucfirst($methodName));

        if ($valid === false) {
            $type = lcfirst($methodName);
            $error = '%s name "%s" is not in camel caps format';
            $data = array(
                $type,
                $methodName,
            );
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        }

        $visibility = 0;
        $static = 0;
        $abstract = 0;
        $final = 0;

        $find = PHP_CodeSniffer_Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;
        $prev = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

        $prefix = $stackPtr;
        while (($prefix = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$methodPrefixes, ($prefix - 1), $prev)) !== false) {
            switch ($tokens[$prefix]['code']) {
                case T_STATIC:
                    $static = $prefix;
                    break;
                case T_ABSTRACT:
                    $abstract = $prefix;
                    break;
                case T_FINAL:
                    $final = $prefix;
                    break;
                default:
                    $visibility = $prefix;
                    break;
            }
        }

        if ($static !== 0 && $static < $visibility) {
            $error = 'The static declaration must come after the visibility declaration';
            $phpcsFile->addError($error, $static, 'StaticBeforeVisibility');
        }

        if ($visibility !== 0 && $final > $visibility) {
            $error = 'The final declaration must precede the visibility declaration';
            $phpcsFile->addError($error, $final, 'FinalAfterVisibility');
        }

        if ($visibility !== 0 && $abstract > $visibility) {
            $error = 'The abstract declaration must precede the visibility declaration';
            $phpcsFile->addError($error, $abstract, 'AbstractAfterVisibility');
        }

    }//end processTokenWithinScope()


}//end class