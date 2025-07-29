export default {
  async fetch(request) {
    const url = new URL(request.url);
    
    // 代理/npm/路径
    if (url.pathname.startsWith('/npm/')) {
      const newUrl = url.pathname.replace('/npm/', 'https://cdn.jsdelivr.net/npm/');
      return fetch(newUrl, {
        headers: request.headers,
        method: request.method
      });
    }
    
    // 代理/gh/路径
    if (url.pathname.startsWith('/gh/')) {
      const newUrl = url.pathname.replace('/gh/', 'https://cdn.jsdelivr.net/gh/');
      return fetch(newUrl, {
        headers: request.headers,
        method: request.method
      });
    }
    
    // 其他请求返回404
    return new Response('Not Found', { status: 404 });
  }
}
