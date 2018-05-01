# TYPO3 extension `javascipt_handler`

This extension re-enables the usage of `javascript:` schema in links defined in
the TYPO3 backend. Originally that usage has been disabled when XSS security fix
[https://typo3.org/security/advisory/typo3-core-sa-2016-018/](https://typo3.org/security/advisory/typo3-core-sa-2016-018/)
was released.

This extension introduces a dedicated link handler for `javascript` that will
re-enable the usage based on individual configuration of allowed expressions.

## Configuration

Modify frontend TypoScript settings in order to define allowed expressions.
The key name `helper` in the example is arbitrary and does not have relevance.

### Define exact `value`

```
config.tx_javascripthandler.allowedExpressions {
    helper.value = javascript:myHelperFunction()
}
```

### Define regular expression `pattern`

```
config.tx_javascripthandler.allowedExpressions {
    helper.pattern = /^javascript:myHelper[a-z]+\(\)$/i
}
```
