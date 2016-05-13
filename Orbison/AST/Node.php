<?php
namespace Orbison\AST;

use Orbison\Exception\TreeException as TreeException;

/*
 * The abstract syntax tree is a recursive structure where every node is the
 * root of the subtree rooted at itself
 */
class Node {
  private $attributes;
  private $parent;
  private $children;

  function __construct($attributes=array()) {
    $this->attributes = $attributes;
    $this->parent = null;
    $this->children = array();
  }

  function addChild($node) {
    $node->parent = $this;
    array_push($this->children, $node);

    return $node;
  }

  function setAttribute($key, $val) {
    $this->attributes[$key] = $val;
  }

  function getParent() {
    if ($this->isRoot()) {
      throw new TreeException("Root of tree has no parent");
    }
    return $this->parent;
  }

  function getChildren() {
    return $this->children;
  }

  function getAttributes() {
    return $this->attributes;
  }

  function getAttribute($attr) {
    if (!$this->hasAttribute($attr)) {
      // TODO: throw exception
    }

    return $this->attributes[$attr];
  }

  function hasAttribute($attr) {
    return array_key_exists($attr, $this->attributes);
  }

  function isRoot() {
    return is_null($this->parent);
  }

  function isLeaf() {
    return count($this->children) === 0;
  }

  function __toString() {
    return $this->getData();
  }

}
?>
