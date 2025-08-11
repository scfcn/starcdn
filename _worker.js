export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    // 仅处理以 /jsdelivr/ 开头的请求
    if (url.pathname.startsWith('/jsdelivr/')) {
      // 移除路径前缀 /jsdelivr
      const newPath = url.pathname.replace('/jsdelivr', '');
      // 重写目标主机和路径
      url.hostname = 'cdn.jsdelivr.net'; // 目标 jsDelivr 域名
      url.pathname = newPath;
      // 转发请求并返回响应
      return fetch(url.toString(), {
        headers: request.headers,
        method: request.method
      });
    }
    // 非 /jsdelivr 请求返回默认页面（如首页）
    return env.ASSETS.fetch(request);
  }
};
