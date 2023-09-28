<?php

/**
 * Zend Data Cache Polyfill
 *
 * In ZendPHP the Zend Data Cache API is no longer present .
 * This is a compatibility layer which allows applications running under ZendPHP to
 * use the old Zend Server Data Cache API with the help of Apc.
 *
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @copyright 2023 Zend by Perforce
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */

/**
 * Stores a variable identified by key into the memory cache.
 * If the given key is already cached, it won't be modified.
 * If a namespace is provided, the key is stored under that namespace.
 * Identical keys can exist under different namespaces.
 *
 * @param  string $key   The data's key. Optional: prefix with a [namespace::].
 * @param  mixed  $value Any PHP object that can be serialized.
 * @param  int    $ttl   Time to live, in seconds.
 *                       The Data Cache keeps an
 *                       object in the cache as
 *                       long as the TTL is not
 *                       expired. Once the TTL is
 *                       expired, the object is
 *                       removed from the cache.
 * @return boolean - TRUE if cache storing done successfully, FALSE otherwise.
 *                   If the given key is already cached, return FALSE.
 */
function zend_shm_cache_add(string $key, $value, int $ttl = 0)
{
    return apcu_add($key, $value, $ttl);
}

/**
 * Stores a variable identified by key into the cache.
 * If a namespace is provided, the key is stored under that namespace.
 * Identical keys can exist under different namespaces.
 *
 * @param  string $key   The data's key. Optional: prefix with a [namespace::].
 * @param  mixed  $value Any PHP object that can be serialized.
 * @param  int    $ttl   Time to live, in seconds.
 *                       The Data Cache keeps an
 *                       object in the cache as
 *                       long as the TTL is not
 *                       expired. Once the TTL is
 *                       expired, the object is
 *                       removed from the cache.
 * @return boolean - FALSE if cache storing fails, TRUE otherwise.
 */
function zend_shm_cache_store(string $key, $value, int $ttl = 0)
{
    return apcu_store($key, $value, $ttl);
}

/**
 * Fetches data from the cache.
 * The key can be prefixed with a namespace to indicate searching within the specified namespace only.
 * If a namespace is not provided, the Data Cache searches for the key in the global namespace.
 *
 * In case the requested entry does not exist in the cache, the callback is called to create a new cache entry. The API uses the callback's return value and stores it into the cache
 *
 * Return Value: mixed - FALSE if no data that matches the key is found, else it returns the stored data. If an array of keys is given, then an array, which its keys are the original keys and the values, are the corresponding stored data values
 *
 * @param  string|array $key      The data key or an array of data keys. Optional for key's name: prefix with a namespace.
 * @param  mixed        $callback In case the requested entry does not exist in the cache,
 *                                the callback is called to create a new cache entry. The
 *                                API uses the callback's return value and stores it into
 *                                the cache
 * @return mixed - FALSE if no data that matches the key is found, else it returns the stored data.
 *                 If an array of keys is given, then an array, which its keys are the original keys and the values,
 *                 are the corresponding stored data values
 */
function zend_shm_cache_fetch($key, $callback = null)
{
    if($callback !== null) {
        error_log("ERROR: zend_shm_cache_fetch does not support callback parameter");
    }
    return apcu_fetch($key);
}

/**
 * Finds and deletes an entry from the cache, using a key to identify it.
 * The key can be prefixed with a namespace to indicate that the key can be deleted within that namespace only.
 * If a namespace is not provided, the Data Cache searches for the key in the global namespace.
 *
 * Return Value: boolean - TRUE on success, FALSE on failure.
 *
 * @param  mixed   $key           The data key or an array of data keys. Optional for key's name: prefix with a namespace.
 * @param  boolean $clusterDelete When set to true (the default), the entry is deleted across the cluster
 * @return boolean - TRUE on success, FALSE on failure.
 */
function zend_shm_cache_delete($key, bool $clusterDelete = false)
{
    if($clusterDelete !== false) {
        error_log("ERROR: zend_shm_cache_delete does not support clusterDelete parameter");
    }

    return apcu_delete($key);
}
