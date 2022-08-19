<?php

class MeiLiBo_Sniffs_Commenting_InlineCommentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_COMMENT);
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int $stackPtr The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $preToken = $prevToken = $phpcsFile->findPrevious(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr - 1),
            null,
            true
        );

        $comment = trim($tokens[$stackPtr]['content']);

        /*
         * Hash comments are not allowed.
        */
        if (0 === strpos($comment, '#')) {
            $phpcsFile->addError('Hash comments are prohibited; found %s'
                , $stackPtr, 'HashComment', array($comment));

            return;
        }

        if (0 !== strpos($comment, '//')) {
            // Not of our concern

            return;
        }

        /*
         * Always have a space before //.
         */
        $beforeCharPtr = $stackPtr - 1;
        if ($tokens[$beforeCharPtr]['type'] !== 'T_WHITESPACE') {
            $phpcsFile->addError('Please put a space before the //; found "%s"'
                , $stackPtr, 'NoSpace', array($comment));

            return;
        }

        /*
         * Always have a space between // and the start of comment text.
         * The exception to this is if the preceding line consists of a single open bracket.
        */

        if (isset($comment{2}) && $comment{2} != ' ') {
            $phpcsFile->addError('Please put a space between the // and the start of comment text; found "%s"'
                , $stackPtr, 'NoSpace', array($comment));

            return;
        }

    }//function

}//class
