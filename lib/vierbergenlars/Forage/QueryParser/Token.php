<?php

namespace vierbergenlars\Forage\QueryParser;

/**
 * A token from the lexer
 */
class Token
{

    /**
     * @internal
     */

    const T_NONE = 0;

    /**
     * A field name token
     */

    const T_FIELD_NAME = 1;

    /**
     * A string token
     */

    const T_STRING = 2;

    /**
     * A field weight token
     */

    const T_FIELD_WEIGHT = 3;

    /**
     * A field value token
     */

    const T_FIELD_VALUE = 4;

    /**
     * A search field token
     */

    const T_FIELD_SEARCH = 5;

    /**
     * The token type
     * @var int
     */
    protected $type;

    /**
     * The token data
     * @var string
     */
    protected $data = null;

    /**
     * The token start position
     * @var int
     */
    protected $startPos;

    /**
     * Create a new token
     * @internal
     * @param int $type The token type
     * @param int $startPos The token start position
     */
    public function __construct($type, $startPos)
    {
        $this->type = $type;
        $this->startPos = $startPos;
    }

    /**
     * Append data to the token
     * @internal
     * @param string $data
     */
    public function addData($data)
    {
        $this->data.=$data;
    }

    /**
     * Updates the token type
     * @internal
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Updates the token type if it is {@link self::T_NONE}
     * @internal
     * @param int $type
     */
    public function setTypeIfNone($type)
    {
        if($this->type == self::T_NONE)
            $this->type = $type;
    }

    /**
     * Check if the token type is {@link self::T_NONE} or the given token
     * @internal
     * @param int $type
     * @return bool
     */
    public function isTypeNoneOr($type)
    {
        return ($this->type == self::T_NONE || $this->type == $type);
    }

    /**
     * Gets the token type
     * @internal
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the token data
     * @internal
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the token start position in the string
     * @internal
     * @return int
     */
    public function getStartPosition()
    {
        return $this->startPos;
    }

    /**
     * Sets the token start position
     * @internal
     * @param int $startPos
     */
    public function setStartPosition($startPos)
    {
        $this->startPos = $startPos;
    }

    /**
     * Gets the token's name.
     * @param int $token A token.
     * @return string
     */
    public static function getName($token)
    {
        $refl = new \ReflectionClass(__CLASS__);
        $constants = $refl->getConstants();
        $name = array_search($token, $constants);
        if($name)
            return $name;
        return 'UNKNOWN_TOKEN';
    }

}
